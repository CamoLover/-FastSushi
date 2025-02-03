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
        Schema::create('produit', function (Blueprint $table) {
            $table->id('id_produit');
            $table->string('nom', 45);
            $table->string('type_produit', 45);
            $table->decimal('prix_ttc', 10, 3);
            $table->decimal('prix_ht', 10, 4);
            $table->string('photo', 200)->nullable();
            $table->decimal('tva', 10, 4);
            $table->string('description', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit');
    }
};
