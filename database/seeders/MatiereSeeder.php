<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matiere;

class MatiereSeeder extends Seeder
{
    public function run(): void
    {
        $matieres = [
            ['nom_matiere' => 'HTML/CSS', 'code_matiere' => 'HTML-CSS', 'description' => 'Langages de base du développement web'],
            ['nom_matiere' => 'JavaScript', 'code_matiere' => 'JS', 'description' => 'Langage de programmation côté client'],
            ['nom_matiere' => 'PHP', 'code_matiere' => 'PHP', 'description' => 'Langage de programmation côté serveur'],
            ['nom_matiere' => 'React', 'code_matiere' => 'REACT', 'description' => 'Framework JavaScript pour interfaces utilisateur'],
            ['nom_matiere' => 'Laravel', 'code_matiere' => 'LARAVEL', 'description' => 'Framework PHP pour applications web'],
            ['nom_matiere' => 'Base de données', 'code_matiere' => 'BDD', 'description' => 'MySQL et conception de bases de données'],
            ['nom_matiere' => 'Design UI/UX', 'code_matiere' => 'UIUX', 'description' => 'Conception d\'interfaces utilisateur'],
            ['nom_matiere' => 'Photoshop', 'code_matiere' => 'PS', 'description' => 'Logiciel de retouche d\'images'],
            ['nom_matiere' => 'Illustrator', 'code_matiere' => 'AI', 'description' => 'Logiciel de création vectorielle'],
            ['nom_matiere' => 'Marketing Digital', 'code_matiere' => 'MKTG', 'description' => 'Stratégies de marketing en ligne'],
            ['nom_matiere' => 'Réseaux Sociaux', 'code_matiere' => 'RS', 'description' => 'Gestion des réseaux sociaux'],
            ['nom_matiere' => 'SEO', 'code_matiere' => 'SEO', 'description' => 'Optimisation pour moteurs de recherche']
        ];

        foreach ($matieres as $matiereData) {
            Matiere::firstOrCreate(
                ['nom_matiere' => $matiereData['nom_matiere']],
                $matiereData
            );
        }
    }
}
