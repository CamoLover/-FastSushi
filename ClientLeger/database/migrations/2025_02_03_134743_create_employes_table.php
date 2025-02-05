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
        Schema::create('employe', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 45);
            $table->string('prenom', 45);
            $table->string('email', 45);
            $table->string('mdp', 45); 
            $table->string('statut_emp', 45); 
            $table->timestamps();
            $table->foreign('id_employe')->references('id_employe')->on('employe')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe');
    }
};
