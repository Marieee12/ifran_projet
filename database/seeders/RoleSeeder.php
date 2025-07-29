<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'nom_role' => 'Administrateur'],
            ['id' => 2, 'nom_role' => 'Coordinateur Pédagogique'],
            ['id' => 3, 'nom_role' => 'Enseignant'],
            ['id' => 4, 'nom_role' => 'Étudiant'],
            ['id' => 5, 'nom_role' => 'Parent'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                array_merge($role, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
