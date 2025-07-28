<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enseignant;
use App\Models\SeanceCours;
use App\Models\Classe;
use App\Models\Matiere;
use Carbon\Carbon;

class EnseignantController extends Controller
{
    /**
     * Affiche le tableau de bord de l'enseignant.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $prochainesSeances = [
            [
                'matiere' => 'Algorithmique',
                'classe' => 'B2 INFO 2024-2025',
                'date' => '30/07/2025',
                'heure' => '09H00 - 11H00',
                'id_seance' => 101,
                'can_saisir' => true,
            ],
            [
                'matiere' => 'Marketing Digital',
                'classe' => 'B2 COMM 2024-2025',
                'date' => '31/07/2025',
                'heure' => '14H00 - 16H00',
                'id_seance' => 102,
                'can_saisir' => false,
            ],
            [
                'matiere' => 'Base de Données',
                'classe' => 'B1 INFO 2024-2025',
                'date' => '01/08/2025',
                'heure' => '10H00 - 12H00',
                'id_seance' => 103,
                'can_saisir' => true,
            ],
        ];

        // --- Section: Présences (Exemple pour la table) ---
        $presencesExemple = [
            ['nom' => 'Toure Myriam', 'present' => true, 'retard' => false, 'absent' => false],
            ['nom' => 'Kone Noah', 'present' => false, 'retard' => true, 'absent' => false],
            ['nom' => 'Kone Yoleine', 'present' => false, 'retard' => false, 'absent' => true],
        ];

        // --- Section: Étudiants droppés (Exemple) ---
        $etudiantsDroppesExemple = [
            ['initiales' => 'TM', 'bg_color' => 'bg-blue-200', 'text_color' => 'text-blue-800', 'nom_complet' => 'Toure Myriam', 'matiere' => 'HTML/CSS', 'pourcentage' => '70%'],
            ['initiales' => 'KN', 'bg_color' => 'bg-green-200', 'text_color' => 'text-green-800', 'nom_complet' => 'Kone Noah', 'matiere' => 'Laravel', 'pourcentage' => '50%'],
            ['initiales' => 'KY', 'bg_color' => 'bg-purple-200', 'text_color' => 'text-purple-800', 'nom_complet' => 'Kone Yoleine', 'matiere' => 'PHP', 'pourcentage' => '60%'],
        ];

        // --- Section: Sessions effectuées (Exemple) ---
        $sessionsEffectuees = [
            ['matiere' => 'HTML/CSS', 'dates' => '17/07 - 22/07'],
            ['matiere' => 'Laravel', 'dates' => '17/07 - 22/07'],
            ['matiere' => 'PHP', 'dates' => '17/07 - 22/07'],
            ['matiere' => 'Docker', 'dates' => '17/07 - 22/07'],
            ['matiere' => 'Docker', 'dates' => '17/07 - 22/07'],
        ];

        // Passage des données à la vue
        return view('enseignants.index', compact(
            'user',
            'prochainesSeances',
            'presencesExemple',
            'etudiantsDroppesExemple',
            'sessionsEffectuees'
        ));
    }
}
