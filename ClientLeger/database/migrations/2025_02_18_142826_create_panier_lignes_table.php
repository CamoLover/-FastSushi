<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('panier_lignes', function (Blueprint $table) {
            $table->id('id_panier_ligne');
            $table->foreignId('id_panier')->constrained('paniers', 'id_panier');
            $table->foreignId('id_produit')->constrained('produits', 'id_produit');
            $table->integer('quantite');
            $table->string('nom', 45);
            $table->decimal('prix_ht', 10, 4);
            $table->decimal('prix_ttc', 10, 3);
            $table->primary('id_panier_ligne');
            $table->index('id_panier');
            $table->index('id_produit');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panier_lignes');
    }
};
