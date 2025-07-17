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
        Schema::create('justifications_absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_presence')->unique()->constrained('presences')->onDelete('cascade')->onUpdate('cascade'); // Unique 0:1
            $table->timestamp('date_justification')->useCurrent()->nullable(false);
            $table->text('raison_justification')->nullable();
            $table->string('document_justificatif_url', 255)->nullable();
            $table->foreignId('justifiee_par_id_coordinateur')->constrained('coordinateurs')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('justifications_absences');
    }
};
