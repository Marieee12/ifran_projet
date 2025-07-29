<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Classe;
use Illuminate\Support\Facades\Hash;

class EtudiantSeeder extends Seeder
{
    public function run()
    {
        // Créer un utilisateur
        $user = User::create([
            'nom_utilisateur' => 'etudiant1',
            'prenom' => 'John',
            'nom' => 'Doe',
            'email' => 'etudiant@test.com',
            'password' => Hash::make('password'),
            'role_id' => 5 // ID pour le rôle étudiant
        ]);

        // Trouver ou créer une classe
        $classe = Classe::first() ?? Classe::create([
            'nom_classe_complet' => 'Classe Test',
            'id_niveau_etude' => 1,
            'id_filiere' => 1,
            'id_annee_academique' => 1
        ]);

        // Créer le profil étudiant
        Etudiant::create([
            'user_id' => $user->id,
            'classe_id' => $classe->id,
            'date_naissance' => '2000-01-01',
            'adresse' => '123 Rue Test',
            'telephone' => '+221 77 000 00 00',
            'est_actif' => true
        ]);
    }
}
