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
    Schema::create('commande_lignes', function (Blueprint $table) {
        $table->id('id_commande_ligne');
        $table->foreignId('id_commande')->constrained('commandes', 'id_commande');
        $table->foreignId('id_produit')->constrained('produits', 'id_produit');
        $table->integer('quantite');
        $table->string('nom', 45);
        $table->decimal('prix_ht', 10, 4);
        $table->decimal('prix_ttc', 10, 3);
        $table->primary('id_commande_ligne');
        $table->index('id_commande');
        $table->index('id_produit');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande_lignes');
    }
};
