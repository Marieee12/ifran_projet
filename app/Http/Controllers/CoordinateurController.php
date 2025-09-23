<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Etudiant;
use App\Models\Absence;
use App\Models\Presence;
use App\Models\JustificationAbsence;
use App\Models\Notification;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Enseignant;
use App\Models\Coordinateur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class CoordinateurController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:coordinateur pédagogique');
    }

    public function index()
    {
        $today = Carbon::today();

        $absencesCount = Absence::whereDate('created_at', $today)->count();

        $justificationsCount = JustificationAbsence::whereNull('justifiee_par_id_coordinateur')->count();

        $coursCount = Cours::whereDate('date_seance', $today)->count();

        $etudiantsCount = Etudiant::count();

        $coursDuJour = Cours::with(['classe', 'matiere', 'enseignant.user'])
            ->whereDate('date_seance', $today)
            ->orderBy('heure_debut')
            ->get()
            ->map(function ($cours) {
                $cours->statut = 'Programmé';
                return $cours;
            });

        $notifications = Notification::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $classes = Classe::with(['filiere', 'niveauEtude'])->get();

        return view('dashboard.coordinateur.dashboard', compact(
            'absencesCount',
            'justificationsCount',
            'coursCount',
            'etudiantsCount',
            'coursDuJour',
            'classes',
            'notifications'
        ));
    }

    public function creerCours()
    {
        $classes = Classe::all();
        $matieres = Matiere::all();
        $enseignants = Enseignant::all();
        return view('dashboard.coordinateur.creer_cours', compact('classes', 'matieres', 'enseignants'));
    }

    public function justifications()
    {
        // Statistiques pour les cartes
        $justificationsEnAttente = JustificationAbsence::whereNull('justifiee_par_id_coordinateur')->count();
        $justificationsValidees = JustificationAbsence::whereNotNull('justifiee_par_id_coordinateur')->count();
        $justificationsTotal = JustificationAbsence::count();

        $justifications = JustificationAbsence::with(['etudiant.user', 'presence.seanceCours.matiere', 'presence.seanceCours.classe'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.coordinateur.justifications', compact(
            'justifications',
            'justificationsEnAttente',
            'justificationsValidees',
            'justificationsTotal'
        ));
    }

    public function absences()
    {
        // Statistiques pour les cartes
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();

        $absencesToday = Presence::where('statut', 'Absent')
            ->whereDate('date_saisie', $today)->count();
        $absencesWeek = Presence::where('statut', 'Absent')
            ->where('date_saisie', '>=', $startOfWeek)->count();
        $absencesJustified = Presence::where('statut', 'Absent')
            ->whereHas('justificationAbsence')->count();
        $absencesNotJustified = Presence::where('statut', 'Absent')
            ->whereDoesntHave('justificationAbsence')->count();

        $absences = Presence::where('statut', 'Absent')
            ->with(['etudiant.user', 'seanceCours.classe', 'seanceCours.matiere'])
            ->orderBy('date_saisie', 'desc')
            ->paginate(10);

        return view('dashboard.coordinateur.absences', compact(
            'absences',
            'absencesToday',
            'absencesWeek',
            'absencesJustified',
            'absencesNotJustified'
        ));
    }

    public function emploiTemps(Request $request)
    {
        $date = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now();
        $semaine = $date->copy()->startOfWeek();
        $finSemaine = $semaine->copy()->endOfWeek();

        $cours = Cours::with(['classe', 'matiere', 'enseignant'])
            ->whereBetween('date_seance', [
                $semaine->format('Y-m-d'),
                $finSemaine->format('Y-m-d')
            ])
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->get();

        return view('dashboard.coordinateur.emploi_temps', compact('cours', 'semaine', 'finSemaine'));
    }

    public function validerJustification(JustificationAbsence $justification)
    {
        $user = Auth::user();
        $coordinateur = Coordinateur::where('user_id', $user->id)->first();

        if (!$coordinateur) {
            return back()->with('error', 'Vous n\'avez pas les droits de coordinateur.');
        }

        $justification->update([
            'justifiee_par_id_coordinateur' => $coordinateur->id,
            'date_justification' => now()
        ]);

        // Créer une notification
        Notification::create([
            'user_id' => $justification->etudiant->user_id,
            'message' => 'Votre justification d\'absence a été validée.',
            'type' => 'info'
        ]);

        return back()->with('success', 'Justification validée avec succès');
    }

    public function refuserJustification(JustificationAbsence $justification)
    {
        $justification->delete();

        // Créer une notification
        Notification::create([
            'user_id' => $justification->etudiant->user_id,
            'message' => 'Votre justification d\'absence a été refusée.',
            'type' => 'urgent'
        ]);

        return back()->with('success', 'Justification refusée');
    }

    public function marquerPresence(Request $request, Cours $cours)
    {
        $request->validate([
            'presences' => 'required|array',
            'presences.*' => 'boolean'
        ]);

        foreach ($request->presences as $etudiantId => $present) {
            if (!$present) {
                Presence::create([
                    'id_etudiant' => $etudiantId,
                    'id_seance_cours' => $cours->id, // Assumant que $cours est une SeanceCours
                    'statut_presence' => 'Absent',
                    'date_saisie' => now(),
                    'saisi_par_id_utilisateur' => Auth::id()
                ]);

                // Notifier le parent
                $etudiant = Etudiant::find($etudiantId);
                if ($etudiant->parents->isNotEmpty()) {
                    foreach ($etudiant->parents as $parent) {
                        Notification::create([
                            'user_id' => $parent->user_id,
                            'message' => "Votre enfant {$etudiant->user->nom} était absent au cours de {$cours->matiere->nom}",
                            'type' => 'urgent'
                        ]);
                    }
                }
            }
        }

        return back()->with('success', 'Présences enregistrées avec succès');
    }



    public function listeCours()
    {
        $cours = Cours::with(['classe', 'matiere', 'enseignant.user'])
            ->orderBy('date_seance', 'desc')
            ->orderBy('heure_debut', 'desc')
            ->paginate(15);

        return view('dashboard.coordinateur.cours.index', compact('cours'));
    }

    public function storeCours(Request $request)
    {
        $request->validate([
            'id_classe' => 'required|exists:classes,id',
            'id_matiere' => 'required|exists:matieres,id',
            'id_enseignant' => 'nullable|exists:enseignants,id',
            'date_seance' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'salle' => 'nullable|string|max:100',
            'type_cours' => 'nullable|in:Presentiel,E-learning,Workshop',
            'description' => 'nullable|string|max:500'
        ]);

        $data = $request->all();
        $data['type_cours'] = $request->input('type_cours', 'Presentiel'); // Valeur par défaut
        $data['id_coordinateur'] = Auth::user()->coordinateur->id ?? null; // ID du coordinateur connecté

        Cours::create($data);

        return redirect()->route('coordinateur.cours.index')
            ->with('success', 'Cours créé avec succès !');
    }

    public function editCours(Cours $cours)
    {
        $classes = Classe::all();
        $matieres = Matiere::all();
        $enseignants = Enseignant::all();

        return view('dashboard.coordinateur.cours.edit', compact('cours', 'classes', 'matieres', 'enseignants'));
    }

    public function updateCours(Request $request, Cours $cours)
    {
        $request->validate([
            'id_classe' => 'required|exists:classes,id',
            'id_matiere' => 'required|exists:matieres,id',
            'id_enseignant' => 'nullable|exists:enseignants,id',
            'date_seance' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'salle' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500'
        ]);

        $cours->update($request->all());

        return redirect()->route('coordinateur.cours.index')
            ->with('success', 'Cours modifié avec succès !');
    }

    public function deleteCours(Cours $cours)
    {
        if ($cours->presences()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce cours car il y a des présences/absences enregistrées.');
        }

        $cours->delete();

        return redirect()->route('coordinateur.cours.index')
            ->with('success', 'Cours supprimé avec succès !');
    }



    public function voirPresences(Cours $cours)
    {
        $etudiants = $cours->classe->etudiants()->with('user')->get();
        $presences = Presence::where('id_seance_cours', $cours->id)
            ->get()
            ->keyBy('id_etudiant');

        return view('dashboard.coordinateur.absences.presences', compact('cours', 'etudiants', 'presences'));
    }

    public function exportAbsences(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'classe_id' => 'nullable|exists:classes,id',
            'format' => 'required|in:csv,excel'
        ]);



       return back()->with('info', 'Fonctionnalité d\'export en développement.');
    }

    public function statistiquesAbsences(Request $request)
    {
        $classeId = $request->get('classe_id');
        $dateDebut = $request->get('date_debut', now()->startOfMonth());
        $dateFin = $request->get('date_fin', now()->endOfMonth());

        $query = Presence::with(['etudiant.user', 'seanceCours.classe', 'seanceCours.matiere'])
            ->where('statut_presence', 'Absent')
            ->whereBetween('date_saisie', [$dateDebut, $dateFin]);

        if ($classeId) {
            $query->whereHas('seanceCours', function($q) use ($classeId) {
                $q->where('id_classe', $classeId);
            });
        }

        $absences = $query->get();
        $classes = Classe::all();

        // Statistiques
        $stats = [
            'total_absences' => $absences->count(),
            'absences_justifiees' => $absences->whereNotNull('justificationAbsence')->count(),
            'absences_non_justifiees' => $absences->whereNull('justificationAbsence')->count(),
            'etudiants_absents' => $absences->pluck('id_etudiant')->unique()->count(),
            'absences_par_classe' => $absences->groupBy('seanceCours.classe.nom_classe')->map->count(),
            'absences_par_matiere' => $absences->groupBy('seanceCours.matiere.nom_matiere')->map->count(),
            'absences_par_jour' => $absences->groupBy(function($absence) {
                return $absence->date_saisie->format('Y-m-d');
            })->map->count()
        ];

        return view('dashboard.coordinateur.absences.statistiques', compact(
            'stats',
            'classes',
            'classeId',
            'dateDebut',
            'dateFin'
        ));
    }
}
