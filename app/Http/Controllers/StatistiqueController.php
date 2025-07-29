<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\SeanceCours;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    /**
     * Dashboard principal avec tous les graphiques pour l'équipe pédagogique
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->role->nom_role;

        // Vérifier que l'utilisateur fait partie de l'équipe pédagogique
        if (!in_array($roleName, ['Coordinateur Pédagogique', 'Enseignant', 'Administrateur'])) {
            abort(403, 'Accès réservé à l\'équipe pédagogique');
        }

        // Filtres selon le rôle
        $classeId = $request->get('classe_id');
        $matiereId = $request->get('matiere_id');
        $periode = $request->get('periode', 'current_trimester');

        // Données pour les graphiques
        $tauxPresenceEtudiants = $this->getTauxPresenceParEtudiant($classeId, $matiereId, $periode, $roleName);
        $tauxPresenceClasses = $this->getTauxPresenceParClasse($periode, $roleName);
        $volumeCoursDispenses = $this->getVolumeCoursDispenses($classeId, $periode, $roleName);
        $volumeCoursCumule = $this->getVolumeCoursCumule($periode, $roleName);

        // Données pour les filtres
        $classes = $this->getAccessibleClasses($roleName);
        $matieres = $this->getAccessibleMatieres($roleName);

        return view('statistiques.dashboard', compact(
            'tauxPresenceEtudiants',
            'tauxPresenceClasses',
            'volumeCoursDispenses',
            'volumeCoursCumule',
            'classes',
            'matieres',
            'classeId',
            'matiereId',
            'periode'
        ));
    }

    /**
     * Graphique du taux de présence par étudiant
     */
    private function getTauxPresenceParEtudiant($classeId, $matiereId, $periode, $roleName)
    {
        $query = DB::table('presences')
            ->join('seances_cours', 'presences.id_seance_cours', '=', 'seances_cours.id')
            ->join('etudiants', 'presences.id_etudiant', '=', 'etudiants.id')
            ->join('users', 'etudiants.id_utilisateur', '=', 'users.id')
            ->join('classes', 'seances_cours.id_classe', '=', 'classes.id')
            ->select(
                'etudiants.id',
                'users.prenom',
                'users.nom',
                'classes.nom_classe',
                DB::raw('COUNT(*) as total_seances'),
                DB::raw('SUM(CASE WHEN presences.statut_presence = "Present" THEN 1 ELSE 0 END) as presents'),
                DB::raw('SUM(CASE WHEN presences.statut_presence = "Retard" THEN 1 ELSE 0 END) as retards'),
                DB::raw('SUM(CASE WHEN presences.statut_presence = "Absent" THEN 1 ELSE 0 END) as absents'),
                DB::raw('ROUND((SUM(CASE WHEN presences.statut_presence IN ("Present", "Retard") THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as taux_presence')
            )
            ->where('seances_cours.est_annulee', false);

        // Filtres de période
        $this->applyPeriodFilter($query, $periode, 'seances_cours.date_seance');

        // Filtres selon le rôle
        if ($roleName === 'Enseignant') {
            $enseignant = Auth::user()->enseignant;
            $query->where('seances_cours.id_enseignant', $enseignant->id);
        }

        if ($classeId) {
            $query->where('seances_cours.id_classe', $classeId);
        }

        if ($matiereId) {
            $query->where('seances_cours.id_matiere', $matiereId);
        }

        $resultats = $query->groupBy('etudiants.id', 'users.prenom', 'users.nom', 'classes.nom_classe')
            ->orderBy('taux_presence', 'desc')
            ->get();

        // Ajouter les codes couleur
        return $resultats->map(function ($etudiant) {
            $couleur = 'green'; // Par défaut vert
            if ($etudiant->taux_presence < 70) {
                $couleur = 'red';
            } elseif ($etudiant->taux_presence < 85) {
                $couleur = 'orange';
            }

            $etudiant->couleur = $couleur;
            return $etudiant;
        });
    }

    /**
     * Graphique du taux de présence par classe
     */
    private function getTauxPresenceParClasse($periode, $roleName)
    {
        $query = DB::table('presences')
            ->join('seances_cours', 'presences.id_seance_cours', '=', 'seances_cours.id')
            ->join('classes', 'seances_cours.id_classe', '=', 'classes.id')
            ->select(
                'classes.id',
                'classes.nom_classe',
                DB::raw('COUNT(*) as total_presences'),
                DB::raw('SUM(CASE WHEN presences.statut_presence IN ("Present", "Retard") THEN 1 ELSE 0 END) as presences_effectives'),
                DB::raw('ROUND((SUM(CASE WHEN presences.statut_presence IN ("Present", "Retard") THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as taux_presence')
            )
            ->where('seances_cours.est_annulee', false);

        $this->applyPeriodFilter($query, $periode, 'seances_cours.date_seance');

        if ($roleName === 'Enseignant') {
            $enseignant = Auth::user()->enseignant;
            $query->where('seances_cours.id_enseignant', $enseignant->id);
        }

        return $query->groupBy('classes.id', 'classes.nom_classe')
            ->orderBy('taux_presence', 'desc')
            ->get();
    }

    /**
     * Graphique du volume de cours dispensés
     */
    private function getVolumeCoursDispenses($classeId, $periode, $roleName)
    {
        $query = DB::table('seances_cours')
            ->join('classes', 'seances_cours.id_classe', '=', 'classes.id')
            ->select(
                'classes.nom_classe',
                'seances_cours.type_cours',
                DB::raw('COUNT(*) as nombre_seances'),
                DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(seances_cours.heure_fin, seances_cours.heure_debut)) / 3600) as heures_total')
            )
            ->where('seances_cours.est_annulee', false);

        $this->applyPeriodFilter($query, $periode, 'seances_cours.date_seance');

        if ($roleName === 'Enseignant') {
            $enseignant = Auth::user()->enseignant;
            $query->where('seances_cours.id_enseignant', $enseignant->id);
        }

        if ($classeId) {
            $query->where('seances_cours.id_classe', $classeId);
        }

        return $query->groupBy('classes.nom_classe', 'seances_cours.type_cours')
            ->orderBy('classes.nom_classe')
            ->get()
            ->groupBy('nom_classe');
    }

    /**
     * Graphique cumulé du volume de cours dispensés
     */
    private function getVolumeCoursCumule($periode, $roleName)
    {
        $query = DB::table('seances_cours')
            ->select(
                DB::raw('DATE_FORMAT(seances_cours.date_seance, "%Y-%m") as mois'),
                'seances_cours.type_cours',
                DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(seances_cours.heure_fin, seances_cours.heure_debut)) / 3600) as heures_total')
            )
            ->where('seances_cours.est_annulee', false);

        $this->applyPeriodFilter($query, $periode, 'seances_cours.date_seance');

        if ($roleName === 'Enseignant') {
            $enseignant = Auth::user()->enseignant;
            $query->where('seances_cours.id_enseignant', $enseignant->id);
        }

        return $query->groupBy('mois', 'seances_cours.type_cours')
            ->orderBy('mois')
            ->get()
            ->groupBy('mois');
    }

    /**
     * Appliquer les filtres de période
     */
    private function applyPeriodFilter($query, $periode, $dateColumn)
    {
        switch ($periode) {
            case 'current_month':
                $query->whereMonth($dateColumn, now()->month)
                      ->whereYear($dateColumn, now()->year);
                break;
            case 'current_trimester':
                $currentMonth = now()->month;
                if ($currentMonth <= 3) {
                    $start = now()->startOfYear();
                    $end = now()->month(3)->endOfMonth();
                } elseif ($currentMonth <= 6) {
                    $start = now()->month(4)->startOfMonth();
                    $end = now()->month(6)->endOfMonth();
                } elseif ($currentMonth <= 9) {
                    $start = now()->month(7)->startOfMonth();
                    $end = now()->month(9)->endOfMonth();
                } else {
                    $start = now()->month(10)->startOfMonth();
                    $end = now()->endOfYear();
                }
                $query->whereBetween($dateColumn, [$start, $end]);
                break;
            case 'current_year':
                $query->whereYear($dateColumn, now()->year);
                break;
        }
    }

    /**
     * Obtenir les classes accessibles selon le rôle
     */
    private function getAccessibleClasses($roleName)
    {
        if ($roleName === 'Enseignant') {
            $enseignant = Auth::user()->enseignant;
            return Classe::whereHas('seancesCours', function ($query) use ($enseignant) {
                $query->where('id_enseignant', $enseignant->id);
            })->get();
        }

        return Classe::all();
    }

    /**
     * Obtenir les matières accessibles selon le rôle
     */
    private function getAccessibleMatieres($roleName)
    {
        if ($roleName === 'Enseignant') {
            $enseignant = Auth::user()->enseignant;
            return Matiere::whereHas('seancesCours', function ($query) use ($enseignant) {
                $query->where('id_enseignant', $enseignant->id);
            })->get();
        }

        return Matiere::all();
    }

    /**
     * Export des données en CSV/Excel
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'presence_etudiants');
        $format = $request->get('format', 'csv');

        // Logique d'export selon le type demandé
        // À implémenter selon les besoins

        return response()->json(['message' => 'Export en cours de développement']);
    }
}
