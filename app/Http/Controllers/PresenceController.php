<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\SeanceCours;
use App\Models\Etudiant;
use App\Models\JustificationAbsence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class PresenceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Afficher l'interface de saisie des présences pour une séance
     */
    public function show(SeanceCours $seance)
    {
        $user = Auth::user();
        $roleName = $user->role->nom_role;

        // Vérifier les permissions selon le type de cours et le rôle
        if (!$this->canManagePresence($seance, $roleName)) {
            abort(403, 'Vous n\'êtes pas autorisé à gérer les présences pour ce type de cours');
        }

        // Vérifier le délai de saisie pour les enseignants
        if ($roleName === 'Enseignant' && !$this->isWithinTimeLimit($seance)) {
            return redirect()->back()->with('error', 'Le délai de saisie de 2 semaines est dépassé');
        }

        $etudiants = $seance->classe->etudiants()->with('user')->get();
        $presences = Presence::where('id_seance_cours', $seance->id)
            ->get()
            ->keyBy('id_etudiant');

        return view('presences.saisie', compact('seance', 'etudiants', 'presences'));
    }

    /**
     * Enregistrer ou mettre à jour les présences
     */
    public function store(Request $request, SeanceCours $seance)
    {
        $user = Auth::user();
        $roleName = $user->role->nom_role;

        if (!$this->canManagePresence($seance, $roleName)) {
            abort(403, 'Vous n\'êtes pas autorisé à gérer les présences pour ce type de cours');
        }

        if ($roleName === 'Enseignant' && !$this->isWithinTimeLimit($seance)) {
            return redirect()->back()->with('error', 'Le délai de saisie de 2 semaines est dépassé');
        }

        $validated = $request->validate([
            'presences' => 'required|array',
            'presences.*' => 'required|in:Present,Retard,Absent'
        ]);

        foreach ($validated['presences'] as $etudiantId => $statut) {
            $presence = Presence::updateOrCreate(
                [
                    'id_seance_cours' => $seance->id,
                    'id_etudiant' => $etudiantId
                ],
                [
                    'statut_presence' => $statut,
                    'date_saisie' => now(),
                    'saisi_par_id_utilisateur' => $user->id,
                    'saisie_dans_delai' => $this->isWithinTimeLimit($seance),
                    'derniere_modification' => now(),
                    'modifie_par_id_utilisateur' => $user->id
                ]
            );
        }

        // Vérifier et créer les notifications pour les étudiants avec trop d'absences
        $this->checkExcessiveAbsences($seance);

        return redirect()->back()->with('success', 'Présences enregistrées avec succès');
    }

    /**
     * Vérifier si l'utilisateur peut gérer les présences pour cette séance
     */
    private function canManagePresence(SeanceCours $seance, string $roleName): bool
    {
        switch ($roleName) {
            case 'Coordinateur Pédagogique':
                // Coordinateur peut gérer E-learning et Workshop
                return in_array($seance->type_cours, ['E-learning', 'Workshop']);

            case 'Enseignant':
                // Enseignant peut gérer seulement ses cours en présentiel
                $user = Auth::user();
                $enseignant = $user->enseignant;
                return $seance->type_cours === 'Presentiel' &&
                       $seance->id_enseignant === $enseignant?->id;

            case 'Administrateur':
                return true;

            default:
                return false;
        }
    }

    /**
     * Vérifier si on est dans le délai de 2 semaines (pour les enseignants)
     */
    private function isWithinTimeLimit(SeanceCours $seance): bool
    {
        $deadline = Carbon::parse($seance->date_seance . ' ' . $seance->heure_debut)
            ->addWeeks(2);

        return now()->lte($deadline);
    }

    /**
     * Vérifier les absences excessives et créer des notifications
     */
    private function checkExcessiveAbsences(SeanceCours $seance)
    {
        $etudiants = $seance->classe->etudiants;

        foreach ($etudiants as $etudiant) {
            $totalSeances = SeanceCours::where('id_classe', $seance->id_classe)
                ->where('id_matiere', $seance->id_matiere)
                ->where('est_annulee', false)
                ->count();

            $absences = Presence::whereHas('seanceCours', function ($query) use ($seance) {
                $query->where('id_classe', $seance->id_classe)
                      ->where('id_matiere', $seance->id_matiere);
            })
            ->where('id_etudiant', $etudiant->id)
            ->where('statut_presence', 'Absent')
            ->count();

            $tauxAbsence = $totalSeances > 0 ? ($absences / $totalSeances) * 100 : 0;

            if ($tauxAbsence >= 30) {
                $this->createExcessiveAbsenceNotification($etudiant, $seance->matiere, $tauxAbsence);
            }
        }
    }

    /**
     * Créer une notification pour absence excessive
     */
    private function createExcessiveAbsenceNotification(Etudiant $etudiant, $matiere, float $tauxAbsence)
    {
        // Notifier le coordinateur
        $coordinateurs = \App\Models\User::whereHas('role', function ($query) {
            $query->where('nom_role', 'Coordinateur Pédagogique');
        })->get();

        // Notifier l'enseignant de la matière
        $enseignants = \App\Models\User::whereHas('enseignant.seancesCours', function ($query) use ($matiere) {
            $query->where('id_matiere', $matiere->id);
        })->get();

        $destinataires = $coordinateurs->merge($enseignants);

        foreach ($destinataires as $destinataire) {
            \App\Models\Notification::create([
                'user_id' => $destinataire->id,
                'type' => 'absence_excessive',
                'type_notification' => 'etudiant_droppe',
                'title' => 'Étudiant en situation d\'échec',
                'message' => "L'étudiant {$etudiant->user->prenom} {$etudiant->user->nom} a dépassé 30% d'absence en {$matiere->nom_matiere} ({$tauxAbsence}%)",
                'etudiant_id' => $etudiant->id,
                'matiere_id' => $matiere->id,
                'taux_absence' => $tauxAbsence,
                'is_read' => false
            ]);
        }
    }

    /**
     * Justifier une absence (coordinateur uniquement)
     */
    public function justify(Request $request, Presence $presence)
    {
        $user = Auth::user();

        if ($user->role->nom_role !== 'Coordinateur Pédagogique') {
            abort(403, 'Seuls les coordinateurs peuvent justifier les absences');
        }

        $validated = $request->validate([
            'motif' => 'required|string|max:500',
            'justification' => 'required|string|max:1000'
        ]);

        JustificationAbsence::create([
            'id_etudiant' => $presence->id_etudiant,
            'id_seance_cours' => $presence->id_seance_cours,
            'motif' => $validated['motif'],
            'justification' => $validated['justification'],
            'date_justification' => now(),
            'justifie_par_id_utilisateur' => $user->id,
            'status' => 'Approuvé'
        ]);

        return redirect()->back()->with('success', 'Absence justifiée avec succès');
    }

    /**
     * Afficher les absences pour les étudiants et parents
     */
    public function showAbsences(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->role->nom_role;

        if ($roleName === 'Étudiant') {
            $etudiant = $user->etudiant;
            if (!$etudiant) {
                abort(404, 'Profil étudiant non trouvé');
            }
            $etudiants = collect([$etudiant]);
        } elseif ($roleName === 'Parent') {
            $parent = $user->parent;
            if (!$parent) {
                abort(404, 'Profil parent non trouvé');
            }
            $etudiants = $parent->etudiants;
        } else {
            abort(403, 'Accès non autorisé');
        }

        $etudiantId = $request->get('etudiant_id', $etudiants->first()?->id);
        $etudiant = $etudiants->where('id', $etudiantId)->first();

        if (!$etudiant) {
            abort(404, 'Étudiant non trouvé');
        }

        $absences = Presence::with(['seanceCours.matiere', 'justificationAbsence'])
            ->where('id_etudiant', $etudiant->id)
            ->where('statut_presence', 'Absent')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($absence) {
                return $absence->justificationAbsence ? 'justifiees' : 'non_justifiees';
            });

        return view('absences.liste', compact('absences', 'etudiant', 'etudiants'));
    }
}
