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
        Schema::table('notifications', function (Blueprint $table) {
            // Ajouter des types spécifiques de notifications
            $table->enum('type_notification', [
                'etudiant_droppe',
                'absence_excessive',
                'justification_demandee',
                'cours_annule',
                'cours_reporte'
            ])->after('type');

            // Informations supplémentaires sur l'étudiant concerné
            $table->foreignId('etudiant_id')->nullable()->constrained('etudiants')->after('type_notification');
            $table->foreignId('matiere_id')->nullable()->constrained('matieres')->after('etudiant_id');
            $table->decimal('taux_absence', 5, 2)->nullable()->after('matiere_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['etudiant_id']);
            $table->dropForeign(['matiere_id']);
            $table->dropColumn(['type_notification', 'etudiant_id', 'matiere_id', 'taux_absence']);
        });
    }
};
