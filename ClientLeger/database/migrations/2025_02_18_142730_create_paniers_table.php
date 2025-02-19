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
        Schema::create('paniers', function (Blueprint $table) {
            $table->id('id_panier');
            $table->string('id_session', 45);
            $table->foreignId('id_client')->nullable()->constrained('clients', 'id_client');
            $table->date('date_panier');
            $table->decimal('montant_tot', 10, 3)->nullable();
            $table->primary('id_panier');
            $table->index('id_client');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paniers');
    }
};
