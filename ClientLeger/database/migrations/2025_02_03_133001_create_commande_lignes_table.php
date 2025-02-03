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
        Schema::create('commande_ligne', function (Blueprint $table) {
            $table->id('id_commande_ligne');
            $table->unsignedBigInteger('id_commande');
            $table->unsignedBigInteger('id_produit');
            $table->integer('quantite');
            $table->string('nom', 45);
            $table->decimal('prix_ht', 10, 4);
            $table->decimal('prix_ttc', 10, 3);
            $table->timestamps();

            $table->foreign('id_commande')->references('id_commande')->on('commandes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_produit')->references('id_produit')->on('produits')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande_ligne');
    }
};
