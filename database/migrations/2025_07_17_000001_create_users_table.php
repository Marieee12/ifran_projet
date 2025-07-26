<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Suppression des champs inutiles
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('restrict')->onUpdate('cascade');
            $table->string('nom_utilisateur', 50)->unique();
            $table->string('prenom', 100);
            $table->string('nom', 100);
            $table->string('telephone', 20)->nullable();
            $table->timestamp('date_creation')->nullable();
            $table->timestamp('derniere_connexion')->nullable();
            $table->boolean('est_actif')->default(true);
            $table->string('password', 255);
            $table->string('email', 255)->unique();
            $table->timestamps();
            $table->rememberToken();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
}
