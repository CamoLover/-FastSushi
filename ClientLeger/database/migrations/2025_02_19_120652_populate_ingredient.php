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
    public function up(): void
    {
        DB::table('produits')->insert([
            ['nom' => 'Fromage', 'photo' => 'fromage.jpg', 'prix_ht' => 2.50, 'type_ingredient' => 'fromage'],
            ['nom' => 'Concombre', 'photo' => 'concombre.jpg', 'prix_ht' => 1.00, 'type_ingredient' => 'légume'],
            ['nom' => 'Avocat', 'photo' => 'avocat.jpg', 'prix_ht' => 2.00, 'type_ingredient' => 'fruit'],
            ['nom' => 'Thon', 'photo' => 'thon.jpg', 'prix_ht' => 3.50, 'type_ingredient' => 'poisson'],
            ['nom' => 'Saumon', 'photo' => 'saumon.jpg', 'prix_ht' => 4.00, 'type_ingredient' => 'poisson'],
            ['nom' => 'Crevettes', 'photo' => 'crevettes.jpg', 'prix_ht' => 5.00, 'type_ingredient' => 'poisson'],
            ['nom' => 'Daurade', 'photo' => 'daurade.jpg', 'prix_ht' => 4.50, 'type_ingredient' => 'poisson'],
            ['nom' => 'Mangue', 'photo' => 'mangue.jpg', 'prix_ht' => 2.50, 'type_ingredient' => 'fruit'],
            ['nom' => 'Boursin', 'photo' => 'boursin.jpg', 'prix_ht' => 3.00, 'type_ingredient' => 'fromage'],
        ]);
    }
};
