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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_annee_academique')->constrained('annees_academiques')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_niveau_etude')->constrained('niveaux_etude')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_filiere')->constrained('filieres')->onDelete('restrict')->onUpdate('cascade');
            $table->string('nom_classe_complet', 150)->unique()->nullable(false);
            $table->unique(['id_annee_academique', 'id_niveau_etude', 'id_filiere'], 'unique_classe_combination');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
