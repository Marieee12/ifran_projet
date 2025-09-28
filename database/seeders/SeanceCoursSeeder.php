<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeanceCours;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Enseignant;
use App\Models\Coordinateur;
use Carbon\Carbon;

class SeanceCoursSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les données nécessaires
        $classes = Classe::all();
        $matieres = Matiere::all();
        $enseignants = Enseignant::all();
        $coordinateur = Coordinateur::first();

        if ($classes->isEmpty() || $matieres->isEmpty() || $enseignants->isEmpty()) {
            echo "Assurez-vous que les classes, matières et enseignants sont créés avant d'exécuter ce seeder.\n";
            return;
        }

        $seances = [];
        $typeCours = ['Presentiel', 'E-learning', 'Workshop'];

        // Créer des séances pour cette semaine et la semaine dernière
        $dates = [
            Carbon::now()->subWeek()->startOfWeek(), // Semaine dernière
            Carbon::now()->startOfWeek(),            // Cette semaine
        ];

        foreach ($dates as $dateDebut) {
            for ($jour = 0; $jour < 5; $jour++) { // Lundi à vendredi
                $dateSeance = $dateDebut->copy()->addDays($jour);

                // 2-3 séances par jour
                $nbSeances = rand(2, 3);
                $heureDebut = 8; // Commencer à 8h

                for ($i = 0; $i < $nbSeances; $i++) {
                    $classe = $classes->random();
                    $matiere = $matieres->random();
                    $enseignant = $enseignants->random();

                    $heure_debut = sprintf('%02d:00', $heureDebut + ($i * 2));
                    $heure_fin = sprintf('%02d:00', $heureDebut + ($i * 2) + 2);

                    $seances[] = [
                        'id_matiere' => $matiere->id,
                        'id_classe' => $classe->id,
                        'id_enseignant' => $enseignant->id,
                        'id_coordinateur' => $coordinateur->id ?? 1,
                        'date_seance' => $dateSeance->format('Y-m-d'),
                        'heure_debut' => $heure_debut,
                        'heure_fin' => $heure_fin,
                        'type_cours' => $typeCours[array_rand($typeCours)],
                        'salle' => 'Salle ' . chr(65 + rand(0, 9)) . rand(10, 99),
                        'est_annulee' => false,
                        'raison_annulation' => null,
                        'id_seance_precedente' => null,
                    ];
                }
            }
        }

        // Insérer toutes les séances
        SeanceCours::insert($seances);

        echo "" . count($seances) . " séances de cours créées avec succès !\n";
        echo "Vous pouvez maintenant marquer les présences depuis le tableau de bord des absences.\n";
    }
}
