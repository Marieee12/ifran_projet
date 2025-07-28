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
            $table->text('raison_annulation')->nullable(); 
            $table->foreignId('id_seance_precedente')->nullable()->constrained('seances_cours')->onDelete('set null')->onUpdate('cascade');
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
