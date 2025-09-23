<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Enseignant;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class EnseignantSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer le rôle enseignant
        $roleEnseignant = Role::where('nom_role', 'enseignant')->first();

        if (!$roleEnseignant) {
            $roleEnseignant = Role::create(['nom_role' => 'enseignant']);
        }

        // Créer des enseignants d'exemple
        $enseignants = [
            [
                'prenom' => 'Jean',
                'nom' => 'Dupont',
                'email' => 'jean.dupont@ifran.com',
                'specialite' => 'Développement Web'
            ],
            [
                'prenom' => 'Marie',
                'nom' => 'Martin',
                'email' => 'marie.martin@ifran.com',
                'specialite' => 'Communication Digitale'
            ],
            [
                'prenom' => 'Pierre',
                'nom' => 'Dubois',
                'email' => 'pierre.dubois@ifran.com',
                'specialite' => 'Création Digitale'
            ],
            [
                'prenom' => 'Sophie',
                'nom' => 'Bernard',
                'email' => 'sophie.bernard@ifran.com',
                'specialite' => 'JavaScript & React'
            ],
            [
                'prenom' => 'Thomas',
                'nom' => 'Petit',
                'email' => 'thomas.petit@ifran.com',
                'specialite' => 'Design UI/UX'
            ],
            [
                'prenom' => 'Emma',
                'nom' => 'Moreau',
                'email' => 'emma.moreau@ifran.com',
                'specialite' => 'Marketing Digital'
            ]
        ];

        foreach ($enseignants as $enseignantData) {
            // Créer l'utilisateur
            $user = User::firstOrCreate(
                ['email' => $enseignantData['email']],
                [
                    'nom_utilisateur' => strtolower($enseignantData['prenom'] . '.' . $enseignantData['nom']),
                    'prenom' => $enseignantData['prenom'],
                    'nom' => $enseignantData['nom'],
                    'email' => $enseignantData['email'],
                    'password' => Hash::make('password'),
                    'role_id' => $roleEnseignant->id
                ]
            );

            // Créer l'enseignant
            Enseignant::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'specialite' => $enseignantData['specialite']
                ]
            );
        }
    }
}
