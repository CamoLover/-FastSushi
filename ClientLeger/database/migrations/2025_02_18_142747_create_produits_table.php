<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id('id_produit');
            $table->string('nom', 45);
            $table->string('type_produit', 45);
            $table->decimal('prix_ttc', 10, 3);
            $table->decimal('prix_ht', 10, 4);
            $table->binary('photo')->nullable();
            $table->string('photo_type', 50)->nullable();
            $table->decimal('tva', 10, 4);
            $table->string('description', 200)->nullable();
            $table->primary('id_produit');
        });

        // Add statement to use MEDIUMBLOB for photo column
        DB::statement("ALTER TABLE produits MODIFY photo MEDIUMBLOB");
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
