<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Coordinateur;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\ParentModel;
use App\Models\Classe;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // L'administrateur est déjà créé par AdminSeeder

        // Créer un coordinateur pédagogique
        $coordinateur_user = User::create([
            'role_id' => 2, // Coordinateur Pédagogique
            'nom_utilisateur' => 'coord1',
            'prenom' => 'Ahmed',
            'nom' => 'BENJELLOUN',
            'email' => 'coordinateur@ifran.ma',
            'password' => Hash::make('coord123'),
            'telephone' => '0123456790',
            'date_creation' => now(),
            'est_actif' => true,
        ]);

        Coordinateur::create([
            'user_id' => $coordinateur_user->id,
            'departement' => 'Informatique',
        ]);

        echo "👤 COORDINATEUR PÉDAGOGIQUE créé:\n";
        echo "   Email: coordinateur@ifran.ma\n";
        echo "   Mot de passe: coord123\n\n";

        // Créer des enseignants
        $enseignants_data = [
            [
                'prenom' => 'Fatima',
                'nom' => 'ALAMI',
                'email' => 'enseignant1@ifran.ma',
                'password' => 'prof123',
                'specialite' => 'Développement Web',
            ],
            [
                'prenom' => 'Youssef',
                'nom' => 'TAZI',
                'email' => 'enseignant2@ifran.ma',
                'password' => 'prof123',
                'specialite' => 'Communication Digitale',
            ],
            [
                'prenom' => 'Aicha',
                'nom' => 'BENALI',
                'email' => 'enseignant3@ifran.ma',
                'password' => 'prof123',
                'specialite' => 'Design Graphique',
            ],
        ];

        echo "👥 ENSEIGNANTS créés:\n";
        foreach ($enseignants_data as $index => $data) {
            $enseignant_user = User::create([
                'role_id' => 3, // Enseignant
                'nom_utilisateur' => 'enseignant' . ($index + 1),
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'telephone' => '012345679' . ($index + 1),
                'date_creation' => now(),
                'est_actif' => true,
            ]);

            Enseignant::create([
                'user_id' => $enseignant_user->id,
                'specialite' => $data['specialite'],
            ]);

            echo "   Email: {$data['email']}\n";
            echo "   Mot de passe: {$data['password']}\n";
        }
        echo "\n";

        // Récupérer les classes existantes ou en créer si nécessaire
        $classes = Classe::all();
        if ($classes->isEmpty()) {
            echo "⚠️  Aucune classe trouvée. Vous devrez créer des classes avant d'ajouter des étudiants.\n\n";
        }

        // Créer des étudiants
        $etudiants_data = [
            [
                'prenom' => 'Omar',
                'nom' => 'LAMRANI',
                'email' => 'etudiant1@ifran.ma',
                'password' => 'etud123',
            ],
            [
                'prenom' => 'Salma',
                'nom' => 'CHAKIR',
                'email' => 'etudiant2@ifran.ma',
                'password' => 'etud123',
            ],
            [
                'prenom' => 'Mehdi',
                'nom' => 'OUALI',
                'email' => 'etudiant3@ifran.ma',
                'password' => 'etud123',
            ],
            [
                'prenom' => 'Khadija',
                'nom' => 'FASSI',
                'email' => 'etudiant4@ifran.ma',
                'password' => 'etud123',
            ],
            [
                'prenom' => 'Amine',
                'nom' => 'BENKIRANE',
                'email' => 'etudiant5@ifran.ma',
                'password' => 'etud123',
            ],
        ];

        echo "👨‍🎓 ÉTUDIANTS créés:\n";
        foreach ($etudiants_data as $index => $data) {
            $etudiant_user = User::create([
                'role_id' => 4, // Étudiant
                'nom_utilisateur' => 'etudiant' . ($index + 1),
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'telephone' => '012345680' . ($index + 1),
                'date_creation' => now(),
                'est_actif' => true,
            ]);

            // Assigner à une classe aléatoire si des classes existent
            $classeId = $classes->isNotEmpty() ? $classes->random()->id : null;

            Etudiant::create([
                'user_id' => $etudiant_user->id,
                'numero_etudiant' => 'ETU' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'date_inscription' => now()->subMonths(rand(1, 12)),
                'classe_id' => $classeId,
                'date_naissance' => now()->subYears(rand(18, 25)),
                'adresse' => 'Adresse fictive ' . ($index + 1),
                'telephone' => '06' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
            ]);

            echo "   Email: {$data['email']}\n";
            echo "   Mot de passe: {$data['password']}\n";
        }
        echo "\n";

        // Créer des parents
        $parents_data = [
            [
                'prenom' => 'Hassan',
                'nom' => 'LAMRANI',
                'email' => 'parent1@ifran.ma',
                'password' => 'parent123',
            ],
            [
                'prenom' => 'Najat',
                'nom' => 'CHAKIR',
                'email' => 'parent2@ifran.ma',
                'password' => 'parent123',
            ],
            [
                'prenom' => 'Rachid',
                'nom' => 'OUALI',
                'email' => 'parent3@ifran.ma',
                'password' => 'parent123',
            ],
        ];

        echo "👨‍👩‍👧‍👦 PARENTS créés:\n";
        foreach ($parents_data as $index => $data) {
            $parent_user = User::create([
                'role_id' => 5, // Parent
                'nom_utilisateur' => 'parent' . ($index + 1),
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'telephone' => '012345690' . ($index + 1),
                'date_creation' => now(),
                'est_actif' => true,
            ]);

            ParentModel::create([
                'user_id' => $parent_user->id,
                'telephone' => '06' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
            ]);

            echo "   Email: {$data['email']}\n";
            echo "   Mot de passe: {$data['password']}\n";
        }
        echo "\n";

        echo "🎉 RÉCAPITULATIF DES COMPTES CRÉÉS:\n";
        echo "=====================================\n\n";

        echo "🔑 ADMINISTRATEUR (Accès complet):\n";
        echo "   📧 Email: admin@ifran.ma\n";
        echo "   🔒 Mot de passe: admin123\n\n";

        echo "👨‍💼 COORDINATEUR PÉDAGOGIQUE:\n";
        echo "   📧 Email: coordinateur@ifran.ma\n";
        echo "   🔒 Mot de passe: coord123\n\n";

        echo "👨‍🏫 ENSEIGNANTS:\n";
        foreach ($enseignants_data as $data) {
            echo "   📧 {$data['email']} | 🔒 {$data['password']}\n";
        }
        echo "\n";

        echo "👨‍🎓 ÉTUDIANTS:\n";
        foreach ($etudiants_data as $data) {
            echo "   📧 {$data['email']} | 🔒 {$data['password']}\n";
        }
        echo "\n";

        echo "👨‍👩‍👧‍👦 PARENTS:\n";
        foreach ($parents_data as $data) {
            echo "   📧 {$data['email']} | 🔒 {$data['password']}\n";
        }
        echo "\n";

        echo "💡 Tous les mots de passe sont visibles ci-dessus pour les tests.\n";
        echo "💡 L'administrateur a tous les droits sur l'application.\n";
        echo "💡 URL: http://127.0.0.1:8003\n";
    }
}
