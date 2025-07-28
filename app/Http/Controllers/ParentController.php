<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Etudiant; // Pour récupérer les informations des enfants
use App\Models\Presence; // Pour les absences
use App\Models\JustificationAbsence; // Pour les justifications
use App\Models\SeanceCours; // Pour l'emploi du temps
use Carbon\Carbon; // Pour manipuler les dates

class ParentController extends Controller
{
    /**
     * Affiche le tableau de bord du parent.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user(); // L'utilisateur parent connecté

        // Données de démonstration pour les absences récentes de l'enfant
        $absencesRecentes = [
            [
                'jour' => 'Lundi 22 Juil.',
                'cours' => 'Mathématiques',
                'horaire' => '09:00 - 11:00',
                'enfant' => 'Kone Sarah'
            ],
            [
                'jour' => 'Mardi 23 Juil.',
                'cours' => 'Français',
                'horaire' => '14:00 - 16:00',
                'enfant' => 'Kone Jean'
            ],
            [
                'jour' => 'Mercredi 24 Juil.',
                'cours' => 'Physique',
                'horaire' => '08:00 - 10:00',
                'enfant' => 'Kone Sarah'
            ],
        ];

        // Données de démonstration pour les justifications en attente
        $justificationsEnAttente = [
            [
                'jour' => 'Lundi 22 Juil.',
                'cours' => 'Mathématiques',
                'horaire' => '09:00 - 11:00',
                'enfant' => 'Kone Sarah',
                'statut' => 'En attente'
            ],
            [
                'jour' => 'Mardi 23 Juil.',
                'cours' => 'Français',
                'horaire' => '14:00 - 16:00',
                'enfant' => 'Kone Jean',
                'statut' => 'En attente'
            ],
        ];

        // Données de démonstration pour le calendrier (jours avec événements)
        $calendarDays = [
            ['day' => '1', 'class' => ''], ['day' => '2', 'class' => ''], ['day' => '3', 'class' => ''], ['day' => '4', 'class' => ''], ['day' => '5', 'class' => ''], ['day' => '6', 'class' => ''], ['day' => '7', 'class' => ''],
            ['day' => '8', 'class' => ''], ['day' => '9', 'class' => ''], ['day' => '10', 'class' => ''], ['day' => '11', 'class' => ''], ['day' => '12', 'class' => ''], ['day' => '13', 'class' => ''], ['day' => '14', 'class' => ''],
            ['day' => '15', 'class' => ''], ['day' => '16', 'class' => ''], ['day' => '17', 'class' => ''], ['day' => '18', 'class' => ''], ['day' => '19', 'class' => ''], ['day' => '20', 'class' => ''], ['day' => '21', 'class' => ''],
            ['day' => '22', 'class' => 'calendar-day-red'],
            ['day' => '23', 'class' => ''],
            ['day' => '24', 'class' => 'calendar-day-red'],
            ['day' => '25', 'class' => ''], ['day' => '26', 'class' => ''], ['day' => '27', 'class' => ''], ['day' => '28', 'class' => ''], ['day' => '29', 'class' => ''], ['day' => '30', 'class' => ''], ['day' => '31', 'class' => ''],
        ];

        // La vue est maintenant 'parent.index'
        return view('parent.index', compact(
            'user',
            'absencesRecentes',
            'justificationsEnAttente',
            'calendarDays'
        ));
    }
}
