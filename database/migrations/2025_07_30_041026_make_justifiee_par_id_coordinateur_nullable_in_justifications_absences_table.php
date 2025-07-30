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
        Schema::table('justifications_absences', function (Blueprint $table) {
            // Supprimer d'abord la contrainte de clé étrangère
            $table->dropForeign(['justifiee_par_id_coordinateur']);

            // Modifier la colonne pour être nullable
            $table->unsignedBigInteger('justifiee_par_id_coordinateur')->nullable()->change();

            // Recréer la contrainte de clé étrangère
            $table->foreign('justifiee_par_id_coordinateur')
                  ->references('id')
                  ->on('coordinateurs')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('justifications_absences', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère
            $table->dropForeign(['justifiee_par_id_coordinateur']);

            // Remettre la colonne comme NOT NULL
            $table->unsignedBigInteger('justifiee_par_id_coordinateur')->nullable(false)->change();

            // Recréer la contrainte de clé étrangère originale
            $table->foreign('justifiee_par_id_coordinateur')
                  ->references('id')
                  ->on('coordinateurs')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }
};
