<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration {
    public function up()
    {
        DB::table('produits')->insert([
            // Entrées
            [
                'nom' => 'Salade Choux',
                'type_produit' => 'Entrée',
                'prix_ht' => 4.5,
                'tva' => 0.10,
                'prix_ttc' => 4.95,
                'description' => 'Délicieuse salade de choux assaisonnée avec une sauce légère et savoureuse.',
                'photo' =>'saladechoux.png'
            ],
        
            [
                'nom' => 'Salade Wakame',
                'type_produit' => 'Entrée',
                'prix_ht' => 5.0,
                'tva' => 0.10,
                'prix_ttc' => 5.5,
                'description' => 'Salade d\'algues wakame marinées, riche en saveurs et en bienfaits nutritionnels.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Salade Fève de soja',
                'type_produit' => 'Entrée',
                'prix_ht' => 4.2,
                'tva' => 0.10,
                'prix_ttc' => 4.62,
                'description' => 'Salade fraîche de fèves de soja avec une touche de sésame et de sauce soja.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Salade Crevettes',
                'type_produit' => 'Entrée',
                'prix_ht' => 6.0,
                'tva' => 0.10,
                'prix_ttc' => 6.6,
                'description' => 'Salade de crevettes croquantes accompagnée d\'une vinaigrette citronnée.',
                'photo' =>'concombre.png'
            ],

            // Soupes 5
            [
                'nom' => 'Soupe Miso',
                'type_produit' => 'Soupe',
                'prix_ht' => 3.5,
                'tva' => 0.10,
                'prix_ttc' => 3.85,
                'description' => 'Soupe japonaise traditionnelle à base de miso et de tofu.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Soupe Ramen crevettes',
                'type_produit' => 'Soupe',
                'prix_ht' => 7.0,
                'tva' => 0.10,
                'prix_ttc' => 7.7,
                'description' => 'Ramen aux crevettes avec un bouillon riche et parfumé.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Soupe Ramen Poulet',
                'type_produit' => 'Soupe',
                'prix_ht' => 6.5,
                'tva' => 0.10,
                'prix_ttc' => 7.15,
                'description' => 'Ramen au poulet tendre dans un bouillon savoureux.',
                'photo' =>'concombre.png'
            ],

            // Plats 8
            [
                'nom' => 'Sushi Saumon',
                'type_produit' => 'Plats',
                'prix_ht' => 8.0,
                'tva' => 0.10,
                'prix_ttc' => 8.8,
                'description' => 'Sushi frais au saumon avec du riz vinaigré.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Sushi Thon',
                'type_produit' => 'Plats',
                'prix_ht' => 8.5,
                'tva' => 0.10,
                'prix_ttc' => 9.35,
                'description' => 'Sushi savoureux au thon rouge.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Sushi Crevettes',
                'type_produit' => 'Plats',
                'prix_ht' => 7.5,
                'tva' => 0.10,
                'prix_ttc' => 8.25,
                'description' => 'Sushi délicat aux crevettes décortiquées.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Sushi Daurade',
                'type_produit' => 'Plats',
                'prix_ht' => 9.0,
                'tva' => 0.10,
                'prix_ttc' => 9.9,
                'description' => 'Sushi raffiné à la daurade tendre.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Sushi Anguille',
                'type_produit' => 'Plats',
                'prix_ht' => 10.0,
                'tva' => 0.10,
                'prix_ttc' => 11.0,
                'description' => 'Sushi unique à l\'anguille grillée et caramélisée.',
                'photo' =>'concombre.png'
            ],

            // Customisations
            [
                'nom' => 'Makis',
                'type_produit' => 'Customisation',
                'prix_ht' => 6.0,
                'tva' => 0.10,
                'prix_ttc' => 6.6,
                'description' => 'Rouleaux de riz garnis d\'ingrédients frais et enroulés dans des feuilles de nori.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'California Rolls',
                'type_produit' => 'Customisation',
                'prix_ht' => 6.5,
                'tva' => 0.10,
                'prix_ttc' => 7.15,
                'description' => 'Makis inversés garnis de crabe et d\'avocat.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Spring Rolls',
                'type_produit' => 'Customisation',
                'prix_ht' => 6.8,
                'tva' => 0.10,
                'prix_ttc' => 7.48,
                'description' => 'Rouleaux de printemps frais et légers.',
                'photo' =>'concombre.png'
            ],

            // Desserts
            [
                'nom' => 'Moelleux Chocolat',
                'type_produit' => 'Desserts',
                'prix_ht' => 5.0,
                'tva' => 0.10,
                'prix_ttc' => 5.5,
                'description' => 'Gâteau fondant au chocolat avec un cœur coulant.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Maki Nutella banane',
                'type_produit' => 'Desserts',
                'prix_ht' => 5.5,
                'tva' => 0.10,
                'prix_ttc' => 6.05,
                'description' => 'Makis sucrés garnis de Nutella et de banane.',
                'photo' =>'concombre.png'
            ],
            [
                'nom' => 'Crispy Nutella pané',
                'type_produit' => 'Desserts',
                'prix_ht' => 6.0,
                'tva' => 0.10,
                'prix_ttc' => 6.6,
                'description' => 'Délice croustillant au Nutella pané.',
                'photo' =>'concombre.png'
            ],
        ]);
    }
};