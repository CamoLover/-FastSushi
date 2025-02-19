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
        Schema::create('compo_paniers', function (Blueprint $table) {
            $table->foreignId('id_panier_ligne')->constrained('panier_lignes', 'id_panier_ligne');
            $table->foreignId('id_ingredient')->constrained('ingredients', 'id_ingredient');
            $table->decimal('prix', 10, 4);
            $table->primary(['id_panier_ligne', 'id_ingredient']);
            $table->index('id_ingredient');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compo_paniers');
    }
};
