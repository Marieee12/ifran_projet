<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Presence;
use App\Models\SeanceCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EtudiantController extends Controller
{
    /**
     * Dashboard de l'étudiant
     */
    public function dashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Récupérer l'étudiant
        $etudiant = Etudiant::where('user_id', $user->id)->first();

        if (!$etudiant) {
            return redirect()->route('login')->with('error', 'Profil étudiant non trouvé');
        }

        // Calculer les statistiques
        $stats = $this->getEtudiantStats($etudiant);

        // Statistiques mensuelles pour le graphique
        $monthlyStats = $this->getMonthlyStats($etudiant);

        // Prochains cours
        $prochainsCours = $this->getProchainsCours($etudiant);

        // Dernières absences
        $dernieresAbsences = $this->getDernieresAbsences($etudiant);

        // Séances récentes
        $seancesRecentes = $this->getSeancesRecentes($etudiant);

        // Vue du dashboard avec les données
        return view('dashboard.etudiant.dashboard', compact(
            'etudiant',
            'stats',
            'monthlyStats',
            'prochainsCours',
            'dernieresAbsences',
            'seancesRecentes'
        ))->with([
            'prochainesSeances' => $prochainsCours,
            'mesAbsences' => $dernieresAbsences
        ]);
    }

    /**
     * Calculer les statistiques de l'étudiant
     */
    private function getEtudiantStats($etudiant)
    {
        $totalSeances = Presence::where('id_etudiant', $etudiant->id)->count();
        $presences = Presence::where('id_etudiant', $etudiant->id)
            ->where('statut_presence', 'Present')->count();
        $absences = Presence::where('id_etudiant', $etudiant->id)
            ->where('statut_presence', 'Absent')->count();
        $retards = Presence::where('id_etudiant', $etudiant->id)
            ->where('statut_presence', 'Retard')->count();

        $tauxPresence = $totalSeances > 0 ? round(($presences / $totalSeances) * 100, 1) : 0;

        return [
            'total_seances' => $totalSeances,
            'presences' => $presences,
            'absences' => $absences,
            'retards' => $retards,
            'taux_presence' => $tauxPresence
        ];
    }

    /**
     * Statistiques mensuelles pour les graphiques
     */
    private function getMonthlyStats($etudiant)
    {
        $stats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyPresences = Presence::where('id_etudiant', $etudiant->id)
                ->whereMonth('date_saisie', $month->month)
                ->whereYear('date_saisie', $month->year)
                ->get();

            $stats[] = [
                'month' => $month->format('M Y'),
                'presences' => $monthlyPresences->where('statut_presence', 'Present')->count(),
                'absences' => $monthlyPresences->where('statut_presence', 'Absent')->count(),
                'retards' => $monthlyPresences->where('statut_presence', 'Retard')->count(),
            ];
        }
        return $stats;
    }

    /**
     * Prochains cours de la semaine
     */
    private function getProchainsCours($etudiant)
    {
        return SeanceCours::where('id_classe', $etudiant->id_classe)
            ->where('date_seance', '>=', now())
            ->where('date_seance', '<=', now()->addDays(7))
            ->with(['matiere', 'enseignant.user'])
            ->orderBy('date_seance', 'asc')
            ->orderBy('heure_debut', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Dernières absences
     */
    private function getDernieresAbsences($etudiant)
    {
        return Presence::where('id_etudiant', $etudiant->id)
            ->where('statut_presence', 'Absent')
            ->with(['seanceCours.matiere', 'seanceCours.enseignant.user', 'justificationAbsence'])
            ->orderBy('date_saisie', 'desc')
            ->limit(3)
            ->get();
    }

    /**
     * Récupérer les séances récentes
     */
    private function getSeancesRecentes($etudiant)
    {
        return SeanceCours::where('id_classe', $etudiant->id_classe)
            ->where('date_seance', '>=', now()->subDays(7))
            ->where('date_seance', '<=', now())
            ->with(['matiere', 'enseignant.user'])
            ->orderBy('date_seance', 'desc')
            ->orderBy('heure_debut', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Récupérer les prochaines séances
     */
    private function getProchainesSeances($etudiant)
    {
        return SeanceCours::where('id_classe', $etudiant->id_classe)
            ->where('date_seance', '>', now())
            ->where('date_seance', '<=', now()->addDays(7))
            ->with(['matiere', 'enseignant.user'])
            ->orderBy('date_seance', 'asc')
            ->orderBy('heure_debut', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Récupérer mes absences récentes
     */
    private function getMesAbsencesRecentes($etudiant)
    {
        return Presence::where('id_etudiant', $etudiant->id)
            ->where('statut_presence', 'Absent')
            ->with(['seanceCours.matiere', 'seanceCours.enseignant.user', 'justificationAbsence'])
            ->orderBy('date_saisie', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Afficher mes absences
     */
    public function mesAbsences(Request $request)
    {
        $user = Auth::user();
        $etudiant = Etudiant::where('user_id', $user->id)->first();

        if (!$etudiant) {
            abort(403, 'Accès non autorisé');
        }

        // Filtres
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->get('date_fin', now()->endOfMonth()->format('Y-m-d'));
        $statut = $request->get('statut'); // 'justifiee', 'non_justifiee', 'en_attente'

        // Récupérer toutes mes absences
        $absencesQuery = Presence::where('id_etudiant', $etudiant->id)
            ->where('statut_presence', 'Absent')
            ->with(['seanceCours.matiere', 'seanceCours.enseignant.user', 'justificationAbsence'])
            ->whereBetween('date_saisie', [$dateDebut, $dateFin]);

        // Appliquer le filtre de statut
        if ($statut === 'justifiee') {
            $absencesQuery->whereHas('justificationAbsence', function($q) {
                $q->where('statut', 'validee');
            });
        } elseif ($statut === 'non_justifiee') {
            $absencesQuery->whereDoesntHave('justificationAbsence');
        } elseif ($statut === 'en_attente') {
            $absencesQuery->whereHas('justificationAbsence', function($q) {
                $q->where('statut', 'en_attente');
            });
        }

        $absences = $absencesQuery->orderBy('date_saisie', 'desc')->paginate(15);

        // Statistiques
        $stats = [
            'total_absences' => Presence::where('id_etudiant', $etudiant->id)
                ->where('statut_presence', 'Absent')->count(),
            'absences_justifiees' => Presence::where('id_etudiant', $etudiant->id)
                ->where('statut_presence', 'Absent')
                ->whereHas('justificationAbsence', function($q) {
                    $q->where('statut', 'validee');
                })->count(),
            'absences_en_attente' => Presence::where('id_etudiant', $etudiant->id)
                ->where('statut_presence', 'Absent')
                ->whereHas('justificationAbsence', function($q) {
                    $q->where('statut', 'en_attente');
                })->count(),
            'absences_non_justifiees' => Presence::where('id_etudiant', $etudiant->id)
                ->where('statut_presence', 'Absent')
                ->whereDoesntHave('justificationAbsence')->count()
        ];

        return view('dashboard.etudiant.absences', compact(
            'etudiant', 'absences', 'stats', 'dateDebut', 'dateFin', 'statut'
        ));
    }

    /**
     * Justifier une absence
     */
    public function justifierAbsence(Request $request, $absenceId)
    {
        $user = Auth::user();
        $etudiant = Etudiant::where('user_id', $user->id)->first();

        if (!$etudiant) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que l'absence appartient à cet étudiant
        $absence = Presence::where('id', $absenceId)
            ->where('id_etudiant', $etudiant->id)
            ->where('statut_presence', 'Absent')
            ->first();

        if (!$absence) {
            abort(404, 'Absence non trouvée');
        }

        // Vérifier s'il n'y a pas déjà une justification
        if ($absence->justificationAbsence) {
            return back()->with('error', 'Cette absence a déjà été justifiée');
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

        \App\Models\JustificationAbsence::create($justificationData);

        return back()->with('success', 'Justification soumise avec succès. Elle sera examinée par le coordinateur.');
    }

    /**
     * Afficher l'emploi du temps de l'étudiant
     */
    public function emploiTemps(Request $request)
    {
        $user = Auth::user();
        $etudiant = Etudiant::where('user_id', $user->id)->first();

        if (!$etudiant) {
            abort(403, 'Accès non autorisé');
        }

        // Obtenir la semaine actuelle ou celle spécifiée
        $semaine = $request->get('semaine', now()->startOfWeek()->format('Y-m-d'));
        $dateDebut = Carbon::parse($semaine)->startOfWeek();
        $dateFin = $dateDebut->copy()->endOfWeek();

        // Récupérer les séances de cours pour cette semaine
        $seances = SeanceCours::where('id_classe', $etudiant->id_classe)
            ->whereBetween('date_seance', [$dateDebut, $dateFin])
            ->with(['matiere', 'enseignant.user'])
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->get();

        // Organiser par jour
        $emploiTemps = collect();
        for ($i = 0; $i < 7; $i++) {
            $jour = $dateDebut->copy()->addDays($i);
            $seancesJour = $seances->filter(function($seance) use ($jour) {
                return Carbon::parse($seance->date_seance)->isSameDay($jour);
            });

            $emploiTemps->push([
                'date' => $jour,
                'jour' => $jour->locale('fr')->dayName,
                'seances' => $seancesJour
            ]);
        }

        return view('dashboard.etudiant.emploi-temps', compact(
            'etudiant', 'emploiTemps', 'dateDebut', 'dateFin'
        ));
    }

    public function index()
    {
        $etudiants = Etudiant::with(['user', 'classe'])->get();
        return view('dashboard.etudiants.index', compact('etudiants'));
    }

    public function create()
    {
        $classes = Classe::all();
        return view('dashboard.etudiants.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'classe_id' => 'required|exists:classes,id',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'telephone' => 'required|string',
        ]);

                // Créer l'utilisateur
        $user = User::create([
            'nom_utilisateur' => strtolower($request->prenom . '.' . $request->nom),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, // ID du rôle étudiant
            'telephone' => $request->telephone,
            'est_actif' => true,
            'date_creation' => now()
        ]);

        // Créer l'étudiant
        $etudiant = Etudiant::create([
            'user_id' => $user->id,
            'classe_id' => $request->classe_id,
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone
        ]);

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant créé avec succès.');
    }

    public function show(Etudiant $etudiant)
    {
        return view('dashboard.etudiants.show', compact('etudiant'));
    }

    public function edit(Etudiant $etudiant)
    {
        $classes = Classe::all();
        return view('dashboard.etudiants.edit', compact('etudiant', 'classes'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $etudiant->user_id,
            'classe_id' => 'required|exists:classes,id',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'telephone' => 'required|string',
        ]);

        // Mettre à jour l'utilisateur
        $etudiant->user->update([
            'nom_utilisateur' => strtolower($request->prenom . '.' . $request->nom),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone
        ]);

        // Mettre à jour l'étudiant
        $etudiant->update([
            'classe_id' => $request->classe_id,
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone
        ]);

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function destroy(Etudiant $etudiant)
    {
        // Supprimer l'utilisateur (et l'étudiant par cascade)
        $etudiant->user->delete();

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }

    /**
     * Affiche le formulaire d'assignation étudiant-classe-niveau
     */
    public function showAssignForm()
    {
        $etudiants = \App\Models\Etudiant::with(['user', 'classe.filiere', 'classe.niveauEtude'])->get();
        $niveaux = \App\Models\NiveauEtude::with('classes.filiere')->get();
        $classes = \App\Models\Classe::with(['filiere', 'niveauEtude'])->get()->groupBy('id_niveau_etude');

        // Organiser les classes par niveau pour l'affichage
        $classesParNiveau = [];
        foreach ($niveaux as $niveau) {
            $classesParNiveau[$niveau->id] = $classes->get($niveau->id, collect());
        }

        return view('dashboard.etudiants.assign', compact('etudiants', 'classesParNiveau', 'niveaux'));
    }

    /**
     * Traite l'assignation d'un étudiant à une classe et un niveau
     */
    public function assign(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'classe_id' => 'required|exists:classes,id',
            'niveau_id' => 'required|exists:niveaux_etude,id',
        ]);

        // Vérifier que la classe appartient bien au niveau sélectionné
        $classe = \App\Models\Classe::findOrFail($request->classe_id);
        if ($classe->niveau_etude_id != $request->niveau_id) {
            return back()->withErrors(['classe_id' => 'La classe sélectionnée ne correspond pas au niveau d\'étude choisi.'])->withInput();
        }

        $etudiant = \App\Models\Etudiant::findOrFail($request->etudiant_id);
        $etudiant->classe_id = $request->classe_id;
        $etudiant->niveau_etude_id = $request->niveau_id;
        $etudiant->save();

        return redirect()->route('etudiants.assign.form')->with('success', 'Étudiant assigné avec succès à la classe et au niveau.');
    }
}
