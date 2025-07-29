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
        $this->middleware('role:coordinateur');
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
        $coursDuJour = Cours::with(['classe', 'matiere', 'enseignant'])
            ->whereDate('date_seance', $today)
            ->orderBy('heure_debut')
            ->get()
            ->map(function ($cours) {
                // Déterminer le statut du cours
                $maintenant = Carbon::now();
                $debut = Carbon::parse($cours->date_seance . ' ' . $cours->heure_debut);
                $fin = Carbon::parse($cours->date_seance . ' ' . $cours->heure_fin);

                if ($maintenant->between($debut, $fin)) {
                    $cours->statut = 'En cours';
                } elseif ($maintenant->lt($debut)) {
                    $cours->statut = 'À venir';
                } else {
                    $cours->statut = 'Terminé';
                }

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
        $justifications = JustificationAbsence::with(['etudiant', 'absence'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('dashboard.coordinateur.justifications', compact('justifications'));
    }

    public function absences()
    {
        $absences = Absence::with(['etudiant', 'cours'])
            ->orderBy('date_absence', 'desc')
            ->paginate(10);
        return view('dashboard.coordinateur.absences', compact('absences'));
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
}
