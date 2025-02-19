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
    Schema::create('compo_commandes', function (Blueprint $table) {
        $table->foreignId('id_commande_ligne')->constrained('commande_lignes', 'id_commande');
        $table->foreignId('id_ingredient')->constrained('ingredients', 'id_ingredient');
        $table->decimal('prix', 10, 4);
        $table->primary(['id_commande_ligne', 'id_ingredient']);
        $table->index('id_ingredient');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compo_commandes');
    }
};
