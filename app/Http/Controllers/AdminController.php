<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classe;
use App\Models\SeanceCours;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\AnneeAcademique;
use App\Models\Role;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administrateur.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {

        // Nombre total d'utilisateurs
        $totalUsers = User::count();
        $currentAcademicYearId = AnneeAcademique::where('est_actuelle', true)->value('id');
        $activeClasses = Classe::when($currentAcademicYearId, function ($query, $currentAcademicYearId) {
            return $query->where('id_annee_academique', $currentAcademicYearId);
        })->count();

        $plannedSessions = SeanceCours::count(); 

        $droppedStudents = 0;

        $recentActivities = [
            [
                'description' => 'Coordinateur Jean a justifié l\'absence de l\'étudiant Marc pour la séance de Maths.',
                'time' => 'il y a 5 minutes'
            ],
            [
                'description' => 'Enseignant Marie a relevé les présences pour le cours de Français.',
                'time' => 'il y a 1 heure'
            ],
            [
                'description' => 'Nouvel utilisateur "Sophie Martin" créé (Rôle: Étudiant).',
                'time' => 'il y a 3 heures'
            ],
            [
                'description' => 'Séance de Physique annulée par Coordinateur Paul.',
                'time' => 'hier'
            ],
            [
                'description' => 'Matière "Base de Données" mise à jour.',
                'time' => 'il y a 2 jours'
            ],
        ];

        $systemAlerts = [
            [
                'type' => 'warning',
                'message' => '5 tentatives de connexion échouées pour l\'utilisateur "admin_test".'
            ],
            [
                'type' => 'info',
                'message' => 'Espace de stockage des documents justificatifs : 75% utilisé.'
            ],
            [
                'type' => 'success',
                'message' => 'Mise à jour du système Laravel Breeze disponible.'
            ],
        ];

        return view('dashboard.index', compact(
            'totalUsers',
            'activeClasses',
            'plannedSessions',
            'droppedStudents',
            'recentActivities',
            'systemAlerts'
        ));
    }
}
