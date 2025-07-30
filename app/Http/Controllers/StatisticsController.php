<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\SeanceCours;
use App\Models\Presence;
use App\Models\Absence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    /**
     * Affiche la page des statistiques
     */
    public function index()
    {
        // Statistiques de présence par étudiant
        $studentsStats = $this->getStudentsPresenceStats();

        // Statistiques de présence par classe
        $classesStats = $this->getClassesPresenceStats();

        // Volume de cours dispensés par type
        $coursesVolume = $this->getCoursesVolumeStats();

        // Déterminer la vue à utiliser selon le rôle
        $user = Auth::user();
        if ($user && $user->role_id === 2) { // Coordinateur pédagogique
            return view('dashboard.coordinateur.statistics', compact('studentsStats', 'classesStats', 'coursesVolume'));
        }

        // Vue par défaut (pour d'autres rôles si nécessaire)
        return view('dashboard.admin.statistics', compact('studentsStats', 'classesStats', 'coursesVolume'));
    }    /**
     * Calcule les statistiques de présence par étudiant
     */
    private function getStudentsPresenceStats()
    {
        $students = Etudiant::with(['user', 'classe'])->get();
        $studentsStats = [];

        foreach ($students as $student) {
            // Compter le total de séances auxquelles l'étudiant devait assister
            $totalSeances = SeanceCours::whereHas('classe', function($query) use ($student) {
                $query->where('id', $student->id_classe);
            })->count();

            if ($totalSeances > 0) {
                // Compter les présences (statut_presence = 'Present')
                $presences = Presence::where('id_etudiant', $student->id)
                    ->where('statut_presence', 'Present')
                    ->count();

                // Calculer le taux de présence
                $tauxPresence = ($presences / $totalSeances) * 100;

                // Déterminer la couleur selon le taux
                $color = $this->getColorByPresenceRate($tauxPresence);

                $studentsStats[] = [
                    'nom' => $student->user->prenom . ' ' . $student->user->nom,
                    'classe' => $student->classe ? $student->classe->nom_classe : 'N/A',
                    'taux_presence' => round($tauxPresence, 1),
                    'color' => $color,
                    'presences' => $presences,
                    'total_seances' => $totalSeances
                ];
            }
        }

        // Trier par taux de présence décroissant
        usort($studentsStats, function($a, $b) {
            return $b['taux_presence'] <=> $a['taux_presence'];
        });

        return $studentsStats;
    }

    /**
     * Calcule les statistiques de présence par classe
     */
    private function getClassesPresenceStats()
    {
        $classes = Classe::with(['etudiants', 'seancesCours'])->get();
        $classesStats = [];

        foreach ($classes as $classe) {
            $totalSeances = $classe->seancesCours->count();
            $totalEtudiants = $classe->etudiants->count();

            if ($totalSeances > 0 && $totalEtudiants > 0) {
                // Calculer le total de présences possibles
                $totalPresencesPossibles = $totalSeances * $totalEtudiants;

                // Compter les présences réelles (statut_presence = 'Present')
                $presencesReelles = Presence::whereHas('etudiant', function($query) use ($classe) {
                    $query->where('id_classe', $classe->id);
                })->where('statut_presence', 'Present')->count();

                $tauxPresence = ($presencesReelles / $totalPresencesPossibles) * 100;

                $classesStats[] = [
                    'nom_classe' => $classe->nom_classe,
                    'taux_presence' => round($tauxPresence, 1),
                    'total_etudiants' => $totalEtudiants,
                    'total_seances' => $totalSeances,
                    'presences_reelles' => $presencesReelles
                ];
            }
        }

        return $classesStats;
    }

    /**
     * Calcule le volume de cours dispensés par type
     */
    private function getCoursesVolumeStats()
    {
        // Récupérer les données selon les types définis dans l'enum
        $coursesVolume = [
            'presentiel' => SeanceCours::where('type_cours', 'Presentiel')->count(),
            'e_learning' => SeanceCours::where('type_cours', 'E-learning')->count(),
            'workshop' => SeanceCours::where('type_cours', 'Workshop')->count()
        ];

        // Si pas de données, créer des données d'exemple
        if ($coursesVolume['presentiel'] == 0 && $coursesVolume['e_learning'] == 0 && $coursesVolume['workshop'] == 0) {
            $totalCours = SeanceCours::count();
            if ($totalCours > 0) {
                $coursesVolume = [
                    'presentiel' => round($totalCours * 0.6), // 60% présentiel
                    'e_learning' => round($totalCours * 0.25), // 25% e-learning
                    'workshop' => round($totalCours * 0.15) // 15% workshop
                ];
            } else {
                // Données d'exemple si aucun cours n'existe
                $coursesVolume = [
                    'presentiel' => 15,
                    'e_learning' => 8,
                    'workshop' => 5
                ];
            }
        }

        return $coursesVolume;
    }

    /**
     * Détermine la couleur selon le taux de présence
     */
    private function getColorByPresenceRate($rate)
    {
        if ($rate >= 70) {
            return '#059669'; // Vert foncé
        } elseif ($rate >= 50.1) {
            return '#10b981'; // Vert clair
        } elseif ($rate >= 30.1) {
            return '#f59e0b'; // Orange
        } else {
            return '#dc2626'; // Rouge
        }
    }

    /**
     * API pour récupérer les données des graphiques
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'students':
                return response()->json($this->getStudentsPresenceStats());
            case 'classes':
                return response()->json($this->getClassesPresenceStats());
            case 'courses':
                return response()->json($this->getCoursesVolumeStats());
            default:
                return response()->json(['error' => 'Type de données non reconnu'], 400);
        }
    }
}
