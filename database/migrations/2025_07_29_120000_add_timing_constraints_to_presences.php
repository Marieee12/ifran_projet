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
        Schema::table('presences', function (Blueprint $table) {
            // Ajouter une colonne pour marquer si la saisie est dans les délais
            $table->boolean('saisie_dans_delai')->default(true)->after('date_saisie');
            // Ajouter une colonne pour la dernière modification
            $table->timestamp('derniere_modification')->nullable()->after('saisie_dans_delai');
            $table->foreignId('modifie_par_id_utilisateur')->nullable()->constrained('users')->after('derniere_modification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropForeign(['modifie_par_id_utilisateur']);
            $table->dropColumn(['saisie_dans_delai', 'derniere_modification', 'modifie_par_id_utilisateur']);
        });
    }
};
