<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SimpleUserListSeeder extends Seeder
{
    /**
     * Affiche tous les utilisateurs avec leurs mots de passe de test
     */
    public function run(): void
    {
        echo "\n🔐 LISTE DES UTILISATEURS IFRAN - PRIORITÉ ADMIN\n";
        echo "===============================================\n\n";

        // Récupérer tous les utilisateurs avec leurs rôles
        $users = User::with('role')->orderBy('role_id')->get();

        if ($users->isEmpty()) {
            echo "❌ Aucun utilisateur trouvé.\n";
            return;
        }

        // Mots de passe par défaut selon le rôle
        $passwords = [
            'Administrateur' => 'admin123',
            'Coordinateur Pédagogique' => 'coord123',
            'Enseignant' => 'prof123',
            'Étudiant' => 'etud123',
            'Parent' => 'parent123'
        ];

        echo "🔑 COMPTE ADMINISTRATEUR (PRIORITÉ):\n";
        echo "====================================\n";

        $admin = $users->where('role_id', 1)->first();
        if ($admin) {
            echo "📧 Email: {$admin->email}\n";
            echo "🔒 Mot de passe: admin123\n";
            echo "👤 Nom: {$admin->prenom} {$admin->nom}\n";
            echo "💼 Accès: COMPLET - Peut tout gérer\n\n";
        } else {
            echo "❌ Aucun administrateur trouvé!\n\n";
        }

        echo "📋 TOUS LES COMPTES UTILISATEURS:\n";
        echo "=================================\n\n";

        foreach ($users as $user) {
            $roleName = $user->role->nom_role ?? 'Sans rôle';
            $defaultPassword = $passwords[$roleName] ?? 'password123';

            $icon = match($roleName) {
                'Administrateur' => '🔑',
                'Coordinateur Pédagogique' => '👨‍💼',
                'Enseignant' => '👨‍🏫',
                'Étudiant' => '👨‍🎓',
                'Parent' => '👨‍👩‍👧‍👦',
                default => '👤'
            };

            echo "{$icon} {$roleName}\n";
            echo "   📧 Email: {$user->email}\n";
            echo "   🔒 Mot de passe: {$defaultPassword}\n";
            echo "   👤 Nom: {$user->prenom} {$user->nom}\n";
            echo "   ✅ Statut: " . ($user->est_actif ? 'Actif' : 'Inactif') . "\n";
            echo "\n";
        }

        echo "🚀 INSTRUCTIONS DE CONNEXION:\n";
        echo "=============================\n";
        echo "🌐 URL: http://127.0.0.1:8003\n";
        echo "📧 Utilisez l'email complet comme identifiant\n";
        echo "🔒 Utilisez les mots de passe listés ci-dessus\n\n";

        echo "⭐ ORDRE DE TEST RECOMMANDÉ:\n";
        echo "============================\n";
        $adminEmail = $admin ? $admin->email : 'Non trouvé';
        echo "1️⃣ ADMIN ({$adminEmail}) - admin123\n";

        $coord = $users->where('role_id', 2)->first();
        if ($coord) {
            echo "2️⃣ COORDINATEUR ({$coord->email}) - coord123\n";
        }

        $enseignant = $users->where('role_id', 3)->first();
        if ($enseignant) {
            echo "3️⃣ ENSEIGNANT ({$enseignant->email}) - prof123\n";
        }

        $etudiant = $users->where('role_id', 4)->first();
        if ($etudiant) {
            echo "4️⃣ ÉTUDIANT ({$etudiant->email}) - etud123\n";
        }

        $parent = $users->where('role_id', 5)->first();
        if ($parent) {
            echo "5️⃣ PARENT ({$parent->email}) - parent123\n";
        }

        echo "\n💡 NOTE: Tous les mots de passe sont des mots de passe de test.\n";
        echo "   En production, ils devront être changés!\n\n";

        // Statistiques
        echo "📊 RÉSUMÉ:\n";
        echo "==========\n";
        $stats = $users->groupBy('role_id')->map->count();
        echo "👥 Total utilisateurs: {$users->count()}\n";
        echo "🔑 Administrateurs: " . ($stats[1] ?? 0) . "\n";
        echo "👨‍💼 Coordinateurs: " . ($stats[2] ?? 0) . "\n";
        echo "👨‍🏫 Enseignants: " . ($stats[3] ?? 0) . "\n";
        echo "👨‍🎓 Étudiants: " . ($stats[4] ?? 0) . "\n";
        echo "👨‍👩‍👧‍👦 Parents: " . ($stats[5] ?? 0) . "\n";
        echo "\n";
    }
}
