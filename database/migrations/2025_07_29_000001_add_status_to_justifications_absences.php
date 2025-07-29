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
            $table->enum('statut', ['en_attente', 'validee', 'refusee'])->default('en_attente')->after('document_justificatif_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('justifications_absences', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
};
