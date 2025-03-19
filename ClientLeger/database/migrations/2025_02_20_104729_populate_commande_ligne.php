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
        DB::table('commande_lignes')->insert([
            // Lignes pour la première commande de Jean Dupont (id_commande = 1)
            [
                'id_commande' => 1,
                'id_produit' => 1, // Salade Choux
                'quantite' => 2,
                'nom' => 'Salade Choux',
                'prix_ht' => 4.5,
                'prix_ttc' => 4.95,
            ],
            [
                'id_commande' => 1,
                'id_produit' => 5, // Soupe Miso
                'quantite' => 1,
                'nom' => 'Soupe Miso',
                'prix_ht' => 3.5,
                'prix_ttc' => 3.85,
            ],
        
            // Lignes pour la deuxième commande de Jean Dupont (id_commande = 2)
            [
                'id_commande' => 2,
                'id_produit' => 3, // Salade Fève de soja
                'quantite' => 1,
                'nom' => 'Salade Fève de soja',
                'prix_ht' => 4.2,
                'prix_ttc' => 4.62,
            ],
            [
                'id_commande' => 2,
                'id_produit' => 7, // Sushi Saumon
                'quantite' => 3,
                'nom' => 'Sushi Saumon',
                'prix_ht' => 8.0,
                'prix_ttc' => 8.8,
            ],
        
            // Lignes pour la première commande de Sophie Martin (id_commande = 3)
            [
                'id_commande' => 3,
                'id_produit' => 2, // Salade Wakame
                'quantite' => 1,
                'nom' => 'Salade Wakame',
                'prix_ht' => 5.0,
                'prix_ttc' => 5.5,
            ],
            [
                'id_commande' => 3,
                'id_produit' => 6, // Soupe Ramen crevettes
                'quantite' => 2,
                'nom' => 'Soupe Ramen crevettes',
                'prix_ht' => 7.0,
                'prix_ttc' => 7.7,
            ],
        
            // Lignes pour la première commande de Paul Lemoine (id_commande = 4)
            [
                'id_commande' => 4,
                'id_produit' => 4, // Salade Crevettes
                'quantite' => 1,
                'nom' => 'Salade Crevettes',
                'prix_ht' => 6.0,
                'prix_ttc' => 6.6,
            ],
            [
                'id_commande' => 4,
                'id_produit' => 8, // Sushi Thon
                'quantite' => 2,
                'nom' => 'Sushi Thon',
                'prix_ht' => 8.5,
                'prix_ttc' => 9.35,
            ],
        
            // Lignes pour la première commande de Camille Morel (id_commande = 5)
            [
                'id_commande' => 5,
                'id_produit' => 9, // Sushi Crevettes
                'quantite' => 1,
                'nom' => 'Sushi Crevettes',
                'prix_ht' => 7.5,
                'prix_ttc' => 8.25,
            ],
            [
                'id_commande' => 5,
                'id_produit' => 10, // Sushi Daurade
                'quantite' => 1,
                'nom' => 'Sushi Daurade',
                'prix_ht' => 9.0,
                'prix_ttc' => 9.9,
            ],
        
            // Lignes pour la première commande de Alexandre Dubois (id_commande = 6)
            [
                'id_commande' => 6,
                'id_produit' => 11, // Sushi Anguille
                'quantite' => 1,
                'nom' => 'Sushi Anguille',
                'prix_ht' => 10.0,
                'prix_ttc' => 11.0,
            ],
            [
                'id_commande' => 6,
                'id_produit' => 12, // Makis
                'quantite' => 2,
                'nom' => 'Makis',
                'prix_ht' => 6.0,
                'prix_ttc' => 6.6,
            ],

        ]);
    }


};
