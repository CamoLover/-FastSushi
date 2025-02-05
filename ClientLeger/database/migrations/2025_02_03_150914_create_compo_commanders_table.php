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
        Schema::create('compo_commande', function (Blueprint $table) {
            $table->integer('id_commande_ligne')->unsigned();
            $table->integer('id_ingredient')->unsigned();
            $table->decimal('prix', 10, 4);
            
            $table->primary(['id_commande_ligne', 'id_ingredient']);
            $table->index('id_ingredient');

            $table->foreign('id_ingredient')->references('id_ingredient')->on('Ingredient')->onDelete('no action')->onUpdate('no action');
            $table->foreign('id_commande_ligne')->references('id_commande_ligne')->on('Commande_ligne')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compo_commande');
    }
};
