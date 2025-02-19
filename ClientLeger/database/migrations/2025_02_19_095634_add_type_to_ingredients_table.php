<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->string('type_ingredient', 45)->after('nom'); // Ajout après la colonne 'nom'
        });
    }

    public function down()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('type_ingredient');
        });
    }
};
