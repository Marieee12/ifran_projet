<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonneesDeBaseSeeder extends Seeder
{
    public function run()
    {
        // Année académique
        DB::table('annees_academiques')->insert([
            'id' => 1,
            'nom_annee' => '2025-2026',
            'date_debut' => '2025-09-01',
            'date_fin' => '2026-06-30',
            'est_actuelle' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Niveau d'étude
        DB::table('niveaux_etude')->insert([
            'id' => 1,
            'nom_niveau' => '1ère année',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Filière
        DB::table('filieres')->insert([
            'id' => 1,
            'nom_filiere' => 'Informatique',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
