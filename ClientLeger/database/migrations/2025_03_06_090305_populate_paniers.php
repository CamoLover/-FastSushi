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
        DB::table('paniers')->insert([
            [
                'id_session' => 'session_123456',
                'id_client' => 1,
                'date_panier' => now(),
                'montant_tot' => 25.50
            ],
            [
                'id_session' => 'session_789012',
                'id_client' => 2,
                'date_panier' => now(),
                'montant_tot' => 40.75
            ],
            [
                'id_session' => 'session_345678',
                'id_client' => 3,
                'date_panier' => now(),
                'montant_tot' => 15.00
            ],

            [
                'id_session' => 'session_567864',
                'id_client' => 4,
                'date_panier' => now(),
                'montant_tot' => 45.00
            ],

            [
                'id_session' => 'session_376329',
                'id_client' => 5,
                'date_panier' => now(),
                'montant_tot' => 24.01
            ]
        ]);
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('paniers')->truncate();
    }
};
