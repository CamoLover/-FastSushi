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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id('id_ingredient');
            $table->string('nom', 45);
            $table->binary('photo')->nullable();
            $table->string('photo_type', 50)->nullable();
            $table->decimal('prix_ht', 10, 4);
            $table->string('type_ingredient');
            $table->primary('id_ingredient');
        });

        // Add statement to use MEDIUMBLOB for photo column
        DB::statement("ALTER TABLE ingredients MODIFY photo MEDIUMBLOB");
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
