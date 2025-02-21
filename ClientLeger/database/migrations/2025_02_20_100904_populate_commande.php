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
         // Insertion des commandes pour chaque client
         DB::table('commandes')->insert([
            // Commandes pour Jean Dupont (id_client = 1)
            [
                'id_client' => 1,
                'date_panier' => '2023-10-01',
                'montant_tot' => 15.45,
                'statut' => 'livré',
            ],
            [
                'id_client' => 1,
                'date_panier' => '2023-10-05',
                'montant_tot' => 22.30,
                'statut' => 'en cours',
            ],

            // Commandes pour Sophie Martin (id_client = 2)
            [
                'id_client' => 2,
                'date_panier' => '2023-10-02',
                'montant_tot' => 18.75,
                'statut' => 'livré',
            ],
            [
                'id_client' => 2,
                'date_panier' => '2023-10-06',
                'montant_tot' => 12.60,
                'statut' => 'en cours',
            ],

            // Commandes pour Paul Lemoine (id_client = 3)
            [
                'id_client' => 3,
                'date_panier' => '2023-10-03',
                'montant_tot' => 20.00,
                'statut' => 'livré',
            ],
            [
                'id_client' => 3,
                'date_panier' => '2023-10-07',
                'montant_tot' => 25.50,
                'statut' => 'en cours',
            ],

            // Commandes pour Camille Morel (id_client = 4)
            [
                'id_client' => 4,
                'date_panier' => '2023-10-04',
                'montant_tot' => 14.85,
                'statut' => 'livré',
            ],
            [
                'id_client' => 4,
                'date_panier' => '2023-10-08',
                'montant_tot' => 19.90,
                'statut' => 'en cours',
            ],

            // Commandes pour Alexandre Dubois (id_client = 5)
            [
                'id_client' => 5,
                'date_panier' => '2023-10-05',
                'montant_tot' => 16.20,
                'statut' => 'livré',
            ],
            [
                'id_client' => 5,
                'date_panier' => '2023-10-09',
                'montant_tot' => 21.75,
                'statut' => 'en cours',
            ],
        ]);
    }

   
};
