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
    Schema::create('commandes', function (Blueprint $table) {
        $table->id('id_commande');
        $table->foreignId('id_client')->constrained('clients', 'id_client');
        $table->date('date_panier');
        $table->decimal('montant_tot', 10, 3)->nullable();
        $table->string('statut', 45);
        $table->primary('id_commande');
        $table->index('id_client');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
