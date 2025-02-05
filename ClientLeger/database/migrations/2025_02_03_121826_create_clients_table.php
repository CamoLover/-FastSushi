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
        Schema::create('Client', function (Blueprint $table) {
            $table->id('id_client');
            $table->string('nom', 45);
            $table->string('prenom', 45);
            $table->string('email', 45)->nullable();
            $table->string('tel', 45)->nullable();
            $table->string('mdp', 45);
            $table->string('adresse', 100)->nullable();
            $table->integer('cp')->nullable();
            $table->string('ville', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Client');
    }
};
