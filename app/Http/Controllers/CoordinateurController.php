<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Etudiant;
use App\Models\Absence;
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
        // Récupérer les statistiques pour aujourd'hui
        $today = Carbon::today();

        // Nombre d'absences du jour
        $absencesCount = Absence::whereDate('created_at', $today)->count();

        // Nombre de justifications en attente
        $justificationsCount = JustificationAbsence::whereNull('justifiee_par_id_coordinateur')->count();

        // Nombre de cours aujourd'hui
        $coursCount = Cours::whereDate('date_seance', $today)->count();

        // Nombre total d'étudiants
        $etudiantsCount = Etudiant::count();

        // Liste des cours du jour
        $coursDuJour = Cours::with(['classe', 'matiere', 'enseignant.user'])
            ->whereDate('date_seance', $today)
            ->orderBy('heure_debut')
            ->get()
            ->map(function ($cours) {
                // Statut simple pour éviter les erreurs de parsing
                $cours->statut = 'Programmé';
                return $cours;
            });

        // Récupérer les dernières notifications
        $notifications = Notification::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.coordinateur.dashboard', compact(
            'absencesCount',
            'justificationsCount',
            'coursCount',
            'etudiantsCount',
            'coursDuJour',
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

        // Liste paginée des justifications
        $justifications = JustificationAbsence::with(['etudiant.user', 'absence.cours.matiere', 'absence.cours.classe'])
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

        $absencesToday = Absence::whereDate('date_absence', $today)->count();
        $absencesWeek = Absence::where('date_absence', '>=', $startOfWeek)->count();
        $absencesJustified = Absence::where('justifie', true)->count();
        $absencesNotJustified = Absence::where('justifie', false)->count();

        // Liste paginée des absences
        $absences = Absence::with(['etudiant.user', 'cours.classe', 'cours.matiere'])
            ->orderBy('date_absence', 'desc')
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
        $coordinateur = Coordinateur::where('id_utilisateur', $user->id)->first();

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
                Absence::create([
                    'etudiant_id' => $etudiantId,
                    'cours_id' => $cours->id,
                    'date_absence' => $cours->date_seance,
                    'justifie' => false,
                    'present' => false
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

    // ===== MÉTHODES CRUD POUR LES COURS =====

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
            'description' => 'nullable|string|max:500'
        ]);

        Cours::create($request->all());

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
        // Vérifier s'il y a des présences/absences liées
        if ($cours->presences()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce cours car il y a des présences/absences enregistrées.');
        }

        $cours->delete();

        return redirect()->route('coordinateur.cours.index')
            ->with('success', 'Cours supprimé avec succès !');
    }
}
