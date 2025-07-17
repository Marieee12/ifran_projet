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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_seance_cours')->constrained('seances_cours')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_etudiant')->constrained('etudiants')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('statut_presence', ['Present', 'Retard', 'Absent'])->nullable(false);
            $table->timestamp('date_saisie')->useCurrent()->nullable(false);
            $table->foreignId('saisi_par_id_utilisateur')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['id_seance_cours', 'id_etudiant']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
