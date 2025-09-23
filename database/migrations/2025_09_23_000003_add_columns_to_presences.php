<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->text('commentaire')->nullable();
            $table->boolean('saisie_dans_delai')->default(true);
            $table->timestamp('derniere_modification')->nullable();
            $table->foreignId('modifie_par_id_utilisateur')->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        // On ne change pas le nom de la colonne, on va plutôt adapter le code pour utiliser le nom actuel
    }

    public function down()
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropColumn([
                'commentaire',
                'saisie_dans_delai',
                'derniere_modification',
                'modifie_par_id_utilisateur'
            ]);
        });
    }
};
