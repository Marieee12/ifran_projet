<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Création de l'administrateur
        User::create([
            'role_id' => Role::where('nom_role', 'Administrateur')->first()->id,
            'nom_utilisateur' => 'admin',
            'prenom' => 'Admin',
            'nom' => 'System',
            'email' => 'admin@ifran.ma',
            'password' => Hash::make('password'),
            'est_actif' => true,
            'date_creation' => now(),
            'derniere_connexion' => null
        ]);
    }
}
