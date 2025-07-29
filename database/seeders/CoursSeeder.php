<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cours;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Enseignant;
use Carbon\Carbon;

class CoursSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les données nécessaires
        $classes = Classe::all();
        $matieres = Matiere::all();
        $enseignants = Enseignant::all();

        if ($classes->isEmpty() || $matieres->isEmpty() || $enseignants->isEmpty()) {
            $this->command->warn('Assurez-vous que les classes, matières et enseignants sont créés avant les cours.');
            return;
        }

        // Créer des cours pour les 2 prochaines semaines
        $dateDebut = Carbon::now()->startOfWeek();

        for ($semaine = 0; $semaine < 4; $semaine++) {
            for ($jour = 0; $jour < 5; $jour++) { // Lundi à Vendredi
                $dateCours = $dateDebut->copy()->addWeeks($semaine)->addDays($jour);

                // 2-4 cours par jour
                $nombreCours = rand(2, 4);
                $heures = ['08:00', '10:00', '14:00', '16:00'];
                $heuresFin = ['09:30', '11:30', '15:30', '17:30'];

                for ($i = 0; $i < $nombreCours; $i++) {
                    $classe = $classes->random();
                    $matiere = $matieres->random();
                    $enseignant = $enseignants->random();

                    // Éviter les doublons pour la même classe le même jour
                    $existeDeja = Cours::where('date_seance', $dateCours->format('Y-m-d'))
                        ->where('id_classe', $classe->id)
                        ->where('heure_debut', $heures[$i])
                        ->exists();

                    if (!$existeDeja) {
                        Cours::create([
                            'id_classe' => $classe->id,
                            'id_matiere' => $matiere->id,
                            'id_enseignant' => $enseignant->id,
                            'date_seance' => $dateCours->format('Y-m-d'),
                            'heure_debut' => $heures[$i],
                            'heure_fin' => $heuresFin[$i],
                            'salle' => 'Salle ' . chr(65 + rand(0, 3)) . rand(1, 5), // A1, B2, etc.
                        ]);
                    }
                }
            }
        }

        $this->command->info('Cours d\'exemple créés avec succès !');
    }
}
