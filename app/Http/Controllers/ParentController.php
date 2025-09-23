<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\Absence;
use App\Models\SeanceCours;
use App\Models\JustificationAbsence;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ParentController extends Controller
{
    /**
     * Test simple pour déboguer
     */
    public function test()
    {
        return response()->json([
            'message' => 'ParentController test fonctionne',
            'user' => Auth::user()->email,
            'role_id' => Auth::user()->role_id,
            'timestamp' => now()
        ]);
    }

    /**
     * Dashboard principal du parent
     */
    public function dashboard()
    {
        try {
            $user = Auth::user();
            $parent = ParentModel::where('user_id', $user->id)->first();

            if (!$parent) {
                return redirect('/')->with('error', 'Profil parent non trouvé');
            }

            // Récupérer les enfants du parent
            $enfants = $parent->etudiants()->with(['classe.filiere', 'classe.niveauEtude'])->get();

            // Calculer les statistiques d'absences
            $totalAbsences = 0;
            $absencesNonJustifiees = 0;
            $justificationsEnAttente = 0;

            foreach ($enfants as $enfant) {
                // Compter les absences de cet enfant (presences avec statut 'Absent')
                $absencesEnfant = \App\Models\Presence::where('id_etudiant', $enfant->id)
                    ->where('statut', 'Absent')
                    ->count();
                $totalAbsences += $absencesEnfant;

                // Compter les absences non justifiées
                $absencesNonJustifieesEnfant = \App\Models\Presence::where('id_etudiant', $enfant->id)
                    ->where('statut', 'Absent')
                    ->whereDoesntHave('justificationAbsence')
                    ->count();
                $absencesNonJustifiees += $absencesNonJustifieesEnfant;

                // Compter les justifications en attente
                $justificationsEnAttenteEnfant = \App\Models\JustificationAbsence::whereHas('presence', function($query) use ($enfant) {
                    $query->where('id_etudiant', $enfant->id)->where('statut', 'Absent');
                })->where('statut', 'en_attente')->count();
                $justificationsEnAttente += $justificationsEnAttenteEnfant;
            }

            // Récupérer les prochains cours des enfants
            $classeIds = $enfants->pluck('id_classe')->filter();
            $prochainsCours = \App\Models\SeanceCours::whereIn('id_classe', $classeIds)
                ->where('date_seance', '>=', now())
                ->orderBy('date_seance', 'asc')
                ->with(['matiere', 'classe'])
                ->limit(5)
                ->get();

            // Statistiques pour le dashboard
            $statistiques = [
                'nombre_enfants' => $enfants->count(),
                'absences_ce_mois' => 0,
                'notifications_non_lues' => 0,
            ];

            // Calculer les absences de ce mois pour tous les enfants
            if ($enfants->isNotEmpty()) {
                $enfantIds = $enfants->pluck('id');
                $statistiques['absences_ce_mois'] = Presence::join('seances_cours', 'presences.id_seance_cours', '=', 'seances_cours.id')
                    ->whereIn('presences.id_etudiant', $enfantIds)
                    ->where('presences.statut', 'Absent')
                    ->whereMonth('seances_cours.date_seance', Carbon::now()->month)
                    ->whereYear('seances_cours.date_seance', Carbon::now()->year)
                    ->count();
            }

            // Récupérer les dernières notifications
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Compter les notifications non lues
            $statistiques['notifications_non_lues'] = Notification::where('user_id', $user->id)
                ->where('lu', false)
                ->count();

            return view('dashboard.parent.dashboard', compact(
                'enfants',
                'totalAbsences',
                'absencesNonJustifiees',
                'justificationsEnAttente',
                'prochainsCours',
                'statistiques',
                'notifications'
            ));

        } catch (\Exception $e) {
            // En cas d'erreur, retourner une réponse JSON pour debug
            return response()->json([
                'error' => 'Erreur dans le dashboard parent',
                'message' => $e->getMessage(),
                'user' => Auth::user()->email,
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }

    /**
     * Afficher la liste des enfants
     */
    public function enfants()
    {
        $user = Auth::user();
        $parent = $user->parent;

        if (!$parent) {
            abort(404, 'Profil parent non trouvé');
        }

        // Récupère les étudiants associés au parent via la table pivot parents_etudiants
        $enfants = $parent->etudiants()
            ->with(['classe.filiere.niveauEtude', 'user'])
            ->whereHas('parents', function($query) use ($parent) {
                $query->where('parents.id', $parent->id);
            })
            ->get();

        // Pour chaque enfant, récupérer ses statistiques
        foreach ($enfants as $enfant) {
            $enfant->totalAbsences = Presence::where('id_etudiant', $enfant->id)
                ->where('statut_presence', 'Absent')
                ->count();

            $enfant->absencesNonJustifiees = Presence::where('id_etudiant', $enfant->id)
                ->where('statut_presence', 'Absent')
                ->whereDoesntHave('justificationAbsence')
                ->count();

            $enfant->derniereCommunication = Notification::where('user_id', $user->id)
                ->where('message', 'like', '%' . $enfant->user->prenom . '%')
                ->latest()
                ->first();
        }

        return view('dashboard.parent.enfants', compact('enfants', 'parent'));
    }

    /**
     * Afficher les absences de tous les enfants
     */
    public function absences(Request $request)
    {
        $user = Auth::user();
        $parent = $user->parent;

        if (!$parent) {
            abort(404, 'Profil parent non trouvé');
        }

        $enfants = $parent->etudiants()->with('user')->get();

        // Filtre par enfant si spécifié
        $etudiantSelectionne = $request->get('etudiant_id');

        $absencesQuery = Presence::with([
            'etudiant.user',
            'seanceCours.matiere',
            'seanceCours.enseignant.user',
            'seanceCours.classe',
            'justificationAbsence'
        ])
        ->where('statut_presence', 'Absent')
        ->whereIn('id_etudiant', $enfants->pluck('id'));

        if ($etudiantSelectionne) {
            $absencesQuery->where('id_etudiant', $etudiantSelectionne);
        }

        $absences = $absencesQuery->orderBy('date_saisie', 'desc')->paginate(15);

        return view('dashboard.parent.absences', compact('absences', 'enfants', 'etudiantSelectionne'));
    }

    /**
     * Soumettre une justification d'absence
     */
    public function soumettreJustification(Request $request, Presence $absence)
    {
        $user = Auth::user();
        $parent = $user->parent;

        if (!$parent) {
            abort(404, 'Profil parent non trouvé');
        }

        // Vérifier que l'absence concerne un enfant du parent
        $enfant = $parent->etudiants()->where('id', $absence->id_etudiant)->first();
        if (!$enfant) {
            abort(403, 'Cette absence ne vous concerne pas');
        }

        $request->validate([
            'raison_justification' => 'required|string|max:1000',
            'document_justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $justificationData = [
            'id_presence' => $absence->id,
            'date_justification' => now(),
            'raison_justification' => $request->raison_justification,
            'statut' => 'en_attente'
        ];

        // Gérer le fichier si présent
        if ($request->hasFile('document_justificatif')) {
            $path = $request->file('document_justificatif')->store('justifications', 'public');
            $justificationData['document_justificatif_url'] = $path;
        }

        JustificationAbsence::create($justificationData);

        return back()->with('success', 'Justification soumise avec succès. Elle sera examinée par le coordinateur.');
    }

    /**
     * Afficher l'emploi du temps des enfants
     */
    public function emploiTemps(Request $request)
    {
        $user = Auth::user();
        $parent = $user->parent;

        if (!$parent) {
            abort(404, 'Profil parent non trouvé');
        }

        $enfants = $parent->etudiants()->with(['classe', 'user'])->get();

        // Filtre par enfant si spécifié
        $etudiantSelectionne = $request->get('etudiant_id');
        $classeIds = $enfants->pluck('classe_id');

        if ($etudiantSelectionne) {
            $enfantSelectionne = $enfants->where('id', $etudiantSelectionne)->first();
            if ($enfantSelectionne) {
                $classeIds = collect([$enfantSelectionne->classe_id]);
            }
        }

        // Récupérer la semaine à afficher
        $semaine = $request->get('semaine', now()->startOfWeek()->format('Y-m-d'));
        $dateDebut = Carbon::parse($semaine)->startOfWeek();
        $dateFin = $dateDebut->copy()->endOfWeek();

        // Récupérer les séances de cours pour la semaine
        $seances = SeanceCours::with(['matiere', 'enseignant.user', 'classe'])
            ->whereIn('id_classe', $classeIds)
            ->whereBetween('date_seance', [$dateDebut, $dateFin])
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->get();

        return view('dashboard.parent.emploi_temps', compact(
            'seances',
            'enfants',
            'etudiantSelectionne',
            'semaine',
            'dateDebut',
            'dateFin'
        ));
    }

    /**
     * Afficher les notifications
     */
    public function notifications()
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Marquer les notifications comme lues
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('dashboard.parent.notifications', compact('notifications'));
    }

    /**
     * Affiche le formulaire d'assignation parent-étudiants (admin uniquement)
     */
    public function showAssignForm()
    {
        $parents = ParentModel::with('user')->get();
        $etudiants = Etudiant::all();
        return view('dashboard.parents.assign', compact('parents', 'etudiants'));
    }

    /**
     * Traite l'assignation parent-étudiants (admin uniquement)
     */
    public function assign(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:parents,id',
            'etudiant_ids' => 'required|array',
            'etudiant_ids.*' => 'exists:etudiants,id',
        ]);
        $parent = ParentModel::findOrFail($request->parent_id);
        $parent->etudiants()->sync($request->etudiant_ids);
        return redirect()->back()->with('success', 'Assignation réalisée avec succès !');
    }

    /**
     * Associer un parent à plusieurs étudiants
     */
    public function store(Request $request)
    {
        $request->validate([
            // ... autres validations ...
            'etudiant_ids' => 'required|array',
            'etudiant_ids.*' => 'exists:etudiants,id',
        ]);

        $parent = ParentModel::create($request->except('etudiant_ids'));
        $parent->etudiants()->attach($request->etudiant_ids);

        return redirect()->route('parents.index')->with('success', 'Parent associé aux étudiants avec succès !');
    }

    /**
     * Désassigne un étudiant d'un parent (admin uniquement)
     */
    public function unassignEtudiant(Request $request, $parent_id, $etudiant_id)
    {
        $parent = ParentModel::findOrFail($parent_id);
        $parent->etudiants()->detach($etudiant_id);
        return redirect()->back()->with('success', 'Étudiant désassigné du parent avec succès !');
    }
}
