<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class ListUsersSeeder extends Seeder
{
    /**
     * Affiche tous les utilisateurs existants et leurs mots de passe de test
     */
    public function run(): void
    {
        echo "\n LISTE COMPLÈTE DES UTILISATEURS IFRAN\n";
        echo "=========================================\n\n";

        // Mots de passe par défaut pour les tests
        $defaultPasswords = [
            'Administrateur' => 'admin123',
            'Coordinateur Pédagogique' => 'coord123',
            'Enseignant' => 'prof123',
            'Étudiant' => 'etud123',
            'Parent' => 'parent123'
        ];

        // Récupérer tous les utilisateurs avec leurs rôles
        $users = User::with('role')->orderBy('role_id')->get();

        if ($users->isEmpty()) {
            echo "Aucun utilisateur trouvé dans la base de données.\n";
            return;
        }

        $usersByRole = $users->groupBy(function($user) {
            return $user->role->nom_role ?? 'Sans rôle';
        });

        foreach ($usersByRole as $roleName => $roleUsers) {
            $defaultPassword = $defaultPasswords[$roleName] ?? 'password123';

            // Icône selon le rôle
            $icon = match($roleName) {
                'Administrateur' => '',
                'Coordinateur Pédagogique' => '',
                'Enseignant' => '',
                'Étudiant' => '',
                'Parent' => '',
                default => ''
            };

            echo "{$icon} {$roleName} (" . $roleUsers->count() . " utilisateur" . ($roleUsers->count() > 1 ? 's' : '') . "):\n";
            echo str_repeat('-', 50) . "\n";

            foreach ($roleUsers as $user) {
                echo " Email: {$user->email}\n";
                echo " Nom: {$user->prenom} {$user->nom}\n";
                echo " Mot de passe de test: {$defaultPassword}\n";
                echo " Téléphone: " . ($user->telephone ?: 'Non renseigné') . "\n";
                echo " Statut: " . ($user->est_actif ? 'Actif' : 'Inactif') . "\n";

                // Informations spécifiques selon le rôle
                if ($roleName === 'Enseignant' && $user->enseignant) {
                    echo " Spécialité: " . ($user->enseignant->specialite ?: 'Non renseignée') . "\n";
                } elseif ($roleName === 'Étudiant' && $user->etudiant) {
                    echo " Numéro étudiant: " . ($user->etudiant->numero_etudiant ?: 'Non renseigné') . "\n";
                    if ($user->etudiant->classe) {
                        echo " Classe: " . $user->etudiant->classe->nom_classe . "\n";
                    }
                } elseif ($roleName === 'Coordinateur Pédagogique' && $user->coordinateur) {
                    echo "Département: " . ($user->coordinateur->departement ?: 'Non renseigné') . "\n";
                }

                echo "\n";
            }
        }

        echo "INSTRUCTIONS DE CONNEXION:\n";
        echo "==============================\n";
        echo "URL: http://127.0.0.1:8003\n";
        echo "Utilisez l'email comme identifiant\n";
        echo "Tous les mots de passe de test sont listés ci-dessus\n\n";

        echo "RECOMMANDATION PRIORITAIRE:\n";
        echo "===============================\n";

        $admin = $users->whereIn('role_id', [1])->first();
        if ($admin) {
            echo "COMMENCEZ PAR L'ADMINISTRATEUR:\n";
            echo "Email: {$admin->email}\n";
            echo "Mot de passe: admin123\n";
            echo "Accès: Complet (gestion de tous les utilisateurs)\n\n";
        }

        echo "RÉPARTITION DES UTILISATEURS:\n";
        foreach ($usersByRole as $roleName => $roleUsers) {
            echo "   • {$roleName}: {$roleUsers->count()}\n";
        }
        echo "   Total: {$users->count()} utilisateurs\n\n";

        // Créer des utilisateurs manquants si nécessaire
        $this->createMissingUsers($defaultPasswords);
    }

    private function createMissingUsers($defaultPasswords)
    {
        $existingEmails = User::pluck('email')->toArray();
        $newUsers = [];

        // Utilisateurs essentiels à créer s'ils n'existent pas
        $essentialUsers = [
            [
                'role_id' => 1,
                'email' => 'admin@ifran.ma',
                'nom_utilisateur' => 'admin_principal',
                'prenom' => 'Super',
                'nom' => 'ADMIN',
                'password' => 'admin123'
            ],
            [
                'role_id' => 2,
                'email' => 'coordinateur@ifran.ma',
                'nom_utilisateur' => 'coord_principal',
                'prenom' => 'Ahmed',
                'nom' => 'COORDINATEUR',
                'password' => 'coord123'
            ]
        ];

        foreach ($essentialUsers as $userData) {
            if (!in_array($userData['email'], $existingEmails)) {
                try {
                    User::create([
                        'role_id' => $userData['role_id'],
                        'nom_utilisateur' => $userData['nom_utilisateur'],
                        'prenom' => $userData['prenom'],
                        'nom' => $userData['nom'],
                        'email' => $userData['email'],
                        'password' => Hash::make($userData['password']),
                        'telephone' => '0123456789',
                        'date_creation' => now(),
                        'est_actif' => true,
                    ]);

                    $newUsers[] = $userData;
                } catch (\Exception $e) {
                    echo "Erreur lors de la création de {$userData['email']}: " . $e->getMessage() . "\n";
                }
            }
        }

        if (!empty($newUsers)) {
            echo " NOUVEAUX UTILISATEURS CRÉÉS:\n";
            echo "================================\n";
            foreach ($newUsers as $user) {
                $role = Role::find($user['role_id']);
                echo "   {$user['email']} ({$role->nom_role})\n";
                echo "   Mot de passe: {$user['password']}\n\n";
            }
        }
    }
}
