<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SeanceCours;
use App\Models\Presence;
use App\Models\AnneeAcademique;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EnseignantController extends Controller
{
    public function dashboard()
    {
        try {
            $user = Auth::user();
            Log::info('Tentative d\'accès au dashboard enseignant', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'route' => request()->route()->getName()
            ]);

            $enseignant = $user->enseignant;
            if (!$enseignant) {
                Log::error('Utilisateur ' . $user->id . ' n\'a pas de profil enseignant associé');
                return redirect()->route('welcome')->with('error', 'Profil enseignant non trouvé');
            }

            // Récupérer l'année académique actuelle
            $anneeActuelle = AnneeAcademique::where('est_actuelle', true)->first();

            // Nombre total de cours de l'enseignant
            $totalCours = SeanceCours::where('id_enseignant', $enseignant->id)
                ->join('classes', 'seances_cours.id_classe', '=', 'classes.id')
                ->where('classes.id_annee_academique', $anneeActuelle->id)
                ->count();

            // Cours à venir
            $coursAVenir = SeanceCours::where('id_enseignant', $enseignant->id)
                ->whereDate('date_seance', '>=', now())
                ->join('classes', 'seances_cours.id_classe', '=', 'classes.id')
                ->where('classes.id_annee_academique', $anneeActuelle->id)
                ->count();

            Log::info('Dashboard enseignant chargé avec succès pour l\'utilisateur ID: ' . $user->id);

            return view('dashboard.enseignant.dashboard', compact('totalCours', 'coursAVenir'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du dashboard enseignant: ' . $e->getMessage());
            return redirect()->route('welcome')->with('error', 'Une erreur est survenue');
        }
    }

    public function cours()
    {
        try {
            $user = Auth::user();
            $enseignant = $user->enseignant;

            $cours = SeanceCours::where('id_enseignant', $enseignant->id)
                ->with(['classe', 'matiere'])
                ->orderBy('date_seance', 'desc')
                ->paginate(10);

            return view('dashboard.enseignant.cours', compact('cours'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des cours: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue');
        }
    }

    public function presences()
    {
        try {
            $user = Auth::user();
            $enseignant = $user->enseignant;

            $seances = SeanceCours::where('id_enseignant', $enseignant->id)
                ->whereDate('date_seance', '=', now()->toDateString())
                ->with(['classe', 'matiere'])
                ->get();

            return view('dashboard.enseignant.presences', compact('seances'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des présences: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue');
        }
    }

    public function emploiTemps()
    {
        try {
            $user = Auth::user();
            $enseignant = $user->enseignant;

            // Récupérer l'année académique actuelle
            $anneeActuelle = AnneeAcademique::where('est_actuelle', true)->first();

            // Récupérer tous les cours de l'enseignant pour l'année en cours
            $seances = SeanceCours::where('id_enseignant', $enseignant->id)
                ->join('classes', 'seances_cours.id_classe', '=', 'classes.id')
                ->where('classes.id_annee_academique', $anneeActuelle->id)
                ->with(['classe', 'matiere'])
                ->whereDate('date_seance', '>=', now()->toDateString())
                ->orderBy('date_seance', 'asc')
                ->orderBy('heure_debut', 'asc')
                ->get();

            // Organiser les séances par jour de la semaine
            $planning = [];
            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

            foreach ($seances as $seance) {
                $dayOfWeek = \Carbon\Carbon::parse($seance->date_seance)->locale('fr')->dayName;
                $dayOfWeekFr = $jours[\Carbon\Carbon::parse($seance->date_seance)->dayOfWeek - 1];
                $planning[$dayOfWeekFr][] = $seance;
            }

            return view('dashboard.enseignant.emploi_temps', compact('planning', 'seances'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de l emploi du temps: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue');
        }
    }
}
