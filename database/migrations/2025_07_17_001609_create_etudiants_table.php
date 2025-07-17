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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_utilisateur')->constrained('users')->onDelete('cascade')->onUpdate('cascade')->unique(); // Unique 1:1
            $table->string('numero_etudiant', 50)->unique()->nullable(false);
            $table->date('date_naissance')->nullable();
            $table->text('adresse')->nullable();
            $table->string('photo_profil_url', 255)->nullable();
            $table->foreignId('id_classe')->constrained('classes')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
