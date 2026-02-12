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
        // Créer la table des rôles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du rôle, par exemple 'admin' ou 'user'
            $table->timestamps();
        });

        // Modifier la table users existante pour ajouter la clé étrangère vers roles
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->default(2); // Ajoute role_id en tant que clé étrangère
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la colonne role_id de la table users
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id'); // Supprime la clé étrangère et la colonne
        });

        // Supprimer la table des rôles
        Schema::dropIfExists('roles');
    }
};
