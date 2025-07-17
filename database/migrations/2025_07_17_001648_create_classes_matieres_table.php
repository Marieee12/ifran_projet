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
        Schema::create('classes_matieres', function (Blueprint $table) {
            $table->foreignId('id_classe')->constrained('classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_matiere')->constrained('matieres')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['id_classe', 'id_matiere']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes_matieres');
    }
};
