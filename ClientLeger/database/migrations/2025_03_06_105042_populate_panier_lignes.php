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

            DB::table('panier_lignes')->insert([
            [
                'id_panier' => 1, // Ici tu peux définir le panier ID selon ta logique
                'id_produit' => 1,
                'quantite' => 1,  // Tu peux ajuster la quantité en fonction de ta logique
                'nom' => "salade choux",
                'prix_ht' => 4.5,
                'prix_ttc' => 4.95,
            ],

            [
                'id_panier' => 2, // Ici tu peux définir le panier ID selon ta logique
                'id_produit' => 5,
                'quantite' => 1,  // Tu peux ajuster la quantité en fonction de ta logique
                'nom' => "Soupe Miso",
                'prix_ht' => 3.5,
                'prix_ttc' => 3.85,
            ],

            [
                'id_panier' => 3, // Ici tu peux définir le panier ID selon ta logique
                'id_produit' => 6,
                'quantite' => 1,  // Tu peux ajuster la quantité en fonction de ta logique
                'nom' => "Soupe Ramen crevettes",
                'prix_ht' => 7.0,
                'prix_ttc' => 7.7,
            ],

            [
                'id_panier' => 4, // Ici tu peux définir le panier ID selon ta logique
                'id_produit' => 8,
                'quantite' => 1,  // Tu peux ajuster la quantité en fonction de ta logique
                'nom' => "Sushi Saumon",
                'prix_ht' => 8.0,
                'prix_ttc' => 8.80,
            ],

            [
                'id_panier' => 5, // Ici tu peux définir le panier ID selon ta logique
                'id_produit' => 17,
                'quantite' => 1,  // Tu peux ajuster la quantité en fonction de ta logique
                'nom' => "Maki Nutella banane",
                'prix_ht' => 5.5,
                'prix_ttc' => 6.05,
            ]

        
        
        
        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
