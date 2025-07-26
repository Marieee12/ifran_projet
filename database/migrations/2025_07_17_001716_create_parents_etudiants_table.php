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
        Schema::create('parents_etudiants', function (Blueprint $table) {
            $table->foreignId('id_parent')->constrained('parents')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_etudiant')->constrained('etudiants')->onDelete('cascade')->onUpdate('cascade');
            $table->string('lien_avec_etudiant', 50)->nullable();
            $table->primary(['id_parent', 'id_etudiant']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents_etudiants');
    }
};
