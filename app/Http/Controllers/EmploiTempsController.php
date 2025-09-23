<?php

namespace App\Http\Controllers;

use App\Models\SeanceCours;
use App\Models\Cours;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class EmploiTempsController extends Controller
{
    use AuthorizesRequests;
    /**
     * Afficher l'emploi du temps selon le rôle de l'utilisateur
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->role->nom_role;

        switch ($roleName) {
            case 'Coordinateur Pédagogique':
                return $this->emploiTempsCoordinateur($request);
            case 'Enseignant':
                return $this->emploiTempsEnseignant($request);
            case 'Étudiant':
                return $this->emploiTempsEtudiant($request);
            case 'Parent':
                return $this->emploiTempsParent($request);
            case 'Administrateur':
                return $this->emploiTempsAdmin($request);
            default:
                abort(403, 'Accès non autorisé');
        }
    }

    /**
     * Emploi du temps pour le coordinateur (vue globale avec droits de modification)
     */
    private function emploiTempsCoordinateur(Request $request)
    {
        $classeId = $request->get('classe_id');
        $semaine = $request->get('semaine', now()->startOfWeek());

        $query = SeanceCours::with(['matiere', 'classe', 'enseignant', 'coordinateur'])
            ->whereBetween('date_seance', [
                Carbon::parse($semaine)->startOfWeek(),
                Carbon::parse($semaine)->endOfWeek()
            ]);

        if ($classeId) {
            $query->where('id_classe', $classeId);
        }

        $seances = $query->orderBy('date_seance')->orderBy('heure_debut')->get();
        $classes = Classe::all();

        return view('coordinateur.emploi-temps', compact('seances', 'classes', 'classeId', 'semaine'));
    }

    /**
     * Emploi du temps pour l'enseignant (ses cours uniquement)
     */
    private function emploiTempsEnseignant(Request $request)
    {
        $user = Auth::user();
        $enseignant = $user->enseignant;

        if (!$enseignant) {
            abort(404, 'Profil enseignant non trouvé');
        }

        $semaine = $request->get('semaine', now()->startOfWeek());

        $seances = SeanceCours::with(['matiere', 'classe', 'enseignant'])
            ->where('id_enseignant', $enseignant->id)
            ->whereBetween('date_seance', [
                Carbon::parse($semaine)->startOfWeek(),
                Carbon::parse($semaine)->endOfWeek()
            ])
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->get();

        return view('enseignant.emploi-temps', compact('seances', 'semaine'));
    }

    /**
     * Emploi du temps pour l'étudiant (sa classe uniquement)
     */
    private function emploiTempsEtudiant(Request $request)
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant || !$etudiant->classe) {
            abort(404, 'Profil étudiant ou classe non trouvé');
        }

        $semaine = $request->get('semaine', now()->startOfWeek());

        $seances = SeanceCours::with(['matiere', 'classe', 'enseignant', 'coordinateur'])
            ->where('id_classe', $etudiant->classe->id)
            ->whereBetween('date_seance', [
                Carbon::parse($semaine)->startOfWeek(),
                Carbon::parse($semaine)->endOfWeek()
            ])
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->get();

        return view('etudiant.emploi-temps', compact('seances', 'semaine'));
    }

    /**
     * Emploi du temps pour le parent (classe de son enfant)
     */
    private function emploiTempsParent(Request $request)
    {
        $user = Auth::user();
        $parent = $user->parent;

        if (!$parent) {
            abort(404, 'Profil parent non trouvé');
        }

        // Récupérer les enfants du parent
        $enfants = $parent->etudiants;
        $etudiantId = $request->get('etudiant_id', $enfants->first()?->id);
        $etudiant = $enfants->where('id', $etudiantId)->first();

        if (!$etudiant || !$etudiant->classe) {
            abort(404, 'Étudiant ou classe non trouvé');
        }

        $semaine = $request->get('semaine', now()->startOfWeek());

        $seances = SeanceCours::with(['matiere', 'classe', 'enseignant', 'coordinateur'])
            ->where('id_classe', $etudiant->classe->id)
            ->whereBetween('date_seance', [
                Carbon::parse($semaine)->startOfWeek(),
                Carbon::parse($semaine)->endOfWeek()
            ])
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->get();

        return view('parent.emploi-temps', compact('seances', 'semaine', 'enfants', 'etudiant'));
    }

    /**
     * Emploi du temps pour l'administrateur (vue globale)
     */
    private function emploiTempsAdmin(Request $request)
    {
        return $this->emploiTempsCoordinateur($request);
    }

    /**
     * Créer ou modifier une séance (coordinateur seulement)
     */
    public function store(Request $request)
    {
        $this->authorize('create', SeanceCours::class);

        $validated = $request->validate([
            'id_matiere' => 'required|exists:matieres,id',
            'id_classe' => 'required|exists:classes,id',
            'id_enseignant' => 'nullable|exists:enseignants,id',
            'date_seance' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'type_cours' => 'required|in:Presentiel,E-learning,Workshop',
            'salle' => 'nullable|string|max:50',
        ]);

        // Définir qui est responsable selon le type de cours
        if (in_array($validated['type_cours'], ['E-learning', 'Workshop'])) {
            $validated['id_coordinateur'] = Auth::user()->coordinateur->id ?? null;
            $validated['id_enseignant'] = null;
        }

        SeanceCours::create($validated);

        return redirect()->back()->with('success', 'Séance créée avec succès');
    }

    /**
     * Annuler ou reporter une séance (coordinateur seulement)
     */
    public function cancel(Request $request, SeanceCours $seance)
    {
        $this->authorize('update', $seance);

        $validated = $request->validate([
            'action' => 'required|in:annuler,reporter',
            'raison_annulation' => 'required|string',
            'nouvelle_date' => 'required_if:action,reporter|date|after:today',
            'nouvelle_heure_debut' => 'required_if:action,reporter|date_format:H:i',
            'nouvelle_heure_fin' => 'required_if:action,reporter|date_format:H:i',
        ]);

        if ($validated['action'] === 'annuler') {
            $seance->update([
                'est_annulee' => true,
                'raison_annulation' => $validated['raison_annulation']
            ]);
        } else {
            // Reporter le cours (créer une nouvelle séance)
            $nouvelleSeance = $seance->replicate();
            $nouvelleSeance->date_seance = Carbon::parse($validated['nouvelle_date'])->format('Y-m-d');
            $nouvelleSeance->heure_debut = Carbon::parse($validated['nouvelle_heure_debut'])->format('H:i:s');
            $nouvelleSeance->heure_fin = Carbon::parse($validated['nouvelle_heure_fin'])->format('H:i:s');
            $nouvelleSeance->id_seance_precedente = $seance->id;
            $nouvelleSeance->save();

            $seance->update([
                'est_annulee' => true,
                'raison_annulation' => $validated['raison_annulation']
            ]);
        }

        return redirect()->back()->with('success', 'Séance ' . $validated['action'] . 'ée avec succès');
    }

    /**
     * Affichage de l'agenda pour le coordinateur
     */
    public function agenda(Request $request)
    {
        // Récupérer le mois et l'année depuis la requête
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);
        $classeId = $request->get('classe_id');

        // Créer une date de début du mois
        $dateDebut = Carbon::createFromDate($annee, $mois, 1)->startOfMonth();
        $dateFin = $dateDebut->copy()->endOfMonth();

        // Construire la requête des cours
        $query = Cours::with(['classe', 'matiere', 'enseignant.user'])
            ->whereBetween('date_seance', [$dateDebut, $dateFin]);

        if ($classeId) {
            $query->where('id_classe', $classeId);
        }

        $cours = $query->orderBy('date_seance')->orderBy('heure_debut')->get();

        // Récupérer toutes les classes pour le filtre
        $classes = Classe::all();

        // Grouper les cours par date
        $coursParDate = $cours->groupBy('date_seance');

        // Générer les données du calendrier
        $calendrier = $this->genererCalendrier($dateDebut, $coursParDate);

        return view('dashboard.coordinateur.emploi-temps.agenda', compact(
            'calendrier',
            'coursParDate',
            'classes',
            'mois',
            'annee',
            'classeId',
            'dateDebut'
        ));
    }

    /**
     * Générer la structure du calendrier
     */
    private function genererCalendrier($dateDebut, $coursParDate)
    {
        $calendrier = [];
        $premierJour = $dateDebut->copy()->startOfMonth();
        $dernierJour = $dateDebut->copy()->endOfMonth();

        // Ajuster pour commencer le calendrier un lundi
        $dateActuelle = $premierJour->copy()->startOfWeek(Carbon::MONDAY);

        // Générer 6 semaines pour couvrir tout le mois
        for ($semaine = 0; $semaine < 6; $semaine++) {
            $calendrier[$semaine] = [];

            for ($jour = 0; $jour < 7; $jour++) {
                $estDansLeMois = $dateActuelle->month == $premierJour->month;
                $coursDate = $dateActuelle->format('Y-m-d');
                $coursJour = $coursParDate->get($coursDate, collect());

                $calendrier[$semaine][$jour] = [
                    'date' => $dateActuelle->copy(),
                    'est_dans_le_mois' => $estDansLeMois,
                    'cours' => $coursJour,
                    'nombre_cours' => $coursJour->count()
                ];

                $dateActuelle->addDay();
            }
        }

        return $calendrier;
    }
}
