<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classe;
use App\Models\Filiere;
use App\Models\NiveauEtude;
use App\Models\AnneeAcademique;

class ClasseSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer l'année académique actuelle
        $anneeActuelle = AnneeAcademique::where('est_actuelle', 1)->first();

        if (!$anneeActuelle) {
            // Si pas d'année actuelle, prendre la première
            $anneeActuelle = AnneeAcademique::first();
        }

        // Créer quelques filières si elles n'existent pas
        $filiereWeb = Filiere::firstOrCreate([
            'nom_filiere' => 'Développement Web'
        ]);

        $filiereComm = Filiere::firstOrCreate([
            'nom_filiere' => 'Communication Digitale'
        ]);

        $filiereCreation = Filiere::firstOrCreate([
            'nom_filiere' => 'Création Digitale'
        ]);

        // Créer le niveau B3 si il n'existe pas
        $niveauB3 = NiveauEtude::firstOrCreate([
            'nom_niveau' => 'B3'
        ]);

        // Créer les classes
        $classes = [
            [
                'nom_classe_complet' => 'B3 Développement Web',
                'id_filiere' => $filiereWeb->id,
                'id_niveau_etude' => $niveauB3->id,
                'id_annee_academique' => $anneeActuelle->id,
            ],
            [
                'nom_classe_complet' => 'B3 Communication Digitale',
                'id_filiere' => $filiereComm->id,
                'id_niveau_etude' => $niveauB3->id,
                'id_annee_academique' => $anneeActuelle->id,
            ],
            [
                'nom_classe_complet' => 'B3 Création Digitale',
                'id_filiere' => $filiereCreation->id,
                'id_niveau_etude' => $niveauB3->id,
                'id_annee_academique' => $anneeActuelle->id,
            ]
        ];

        foreach ($classes as $classeData) {
            Classe::firstOrCreate(
                ['nom_classe_complet' => $classeData['nom_classe_complet']],
                $classeData
            );
        }
    }
}
