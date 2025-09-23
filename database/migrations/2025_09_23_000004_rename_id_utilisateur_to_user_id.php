<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renommer la colonne id_utilisateur en user_id dans toutes les tables
        Schema::table('coordinateurs', function (Blueprint $table) {
            $table->renameColumn('id_utilisateur', 'user_id');
        });

        Schema::table('enseignants', function (Blueprint $table) {
            $table->renameColumn('id_utilisateur', 'user_id');
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->renameColumn('id_utilisateur', 'user_id');
        });

        Schema::table('etudiants', function (Blueprint $table) {
            $table->renameColumn('id_utilisateur', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les anciens noms des colonnes
        Schema::table('coordinateurs', function (Blueprint $table) {
            $table->renameColumn('user_id', 'id_utilisateur');
        });

        Schema::table('enseignants', function (Blueprint $table) {
            $table->renameColumn('user_id', 'id_utilisateur');
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->renameColumn('user_id', 'id_utilisateur');
        });

        Schema::table('etudiants', function (Blueprint $table) {
            $table->renameColumn('user_id', 'id_utilisateur');
        });
    }
};
