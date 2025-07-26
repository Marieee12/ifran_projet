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
        Schema::create('seances_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_matiere')->constrained('matieres')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_classe')->constrained('classes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_enseignant')->nullable()->constrained('enseignants')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('id_coordinateur')->nullable()->constrained('coordinateurs')->onDelete('set null')->onUpdate('cascade');
            $table->date('date_seance')->nullable(false);
            $table->time('heure_debut')->nullable(false);
            $table->time('heure_fin')->nullable(false);
            $table->enum('type_cours', ['Presentiel', 'E-learning', 'Workshop'])->nullable(false);
            $table->string('salle', 50)->nullable();
            $table->boolean('est_annulee')->default(false)->nullable(false);
            $table->text('raison_annulation')->nullable(); // Nouvelle colonne pour la raison d'annulation
            $table->foreignId('id_seance_precedente')->nullable()->constrained('seances_cours')->onDelete('set null')->onUpdate('cascade'); // Nouvelle FK auto-référencée
            // Suppression des anciennes colonnes de report :
            // $table->date('date_report')->nullable();
            // $table->time('heure_report_debut')->nullable();
            // $table->time('heure_report_fin')->nullable();
            // $table->text('raison_annulation_report')->nullable();
            // $table->timestamps(); // Si vous voulez created_at/updated_at pour cette table

            // Contrainte CHECK (si votre SGBD la supporte et si vous la gérez manuellement)
            // CONSTRAINT chk_seance_responsable CHECK (id_enseignant IS NOT NULL OR id_coordinateur IS NOT NULL)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances_cours');
    }
};
