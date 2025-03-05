<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande_ligne;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class BestSellerController extends Controller
{
    public function bestSellers()
    {
        // Récupération des produits les plus vendus avec leur image
        $bestSellers = Commande_ligne::select(
                'commande_lignes.id_produit',
                'produits.nom',
                'produits.photo',
                DB::raw('SUM(quantite) as total_quantite_vendue'),
                DB::raw('COUNT(DISTINCT commande_lignes.id_commande) as nombre_commandes')
            )
            ->join('produits', 'commande_lignes.id_produit', '=', 'produits.id_produit')
            ->groupBy('commande_lignes.id_produit', 'produits.nom', 'produits.photo')
            ->orderByDesc('total_quantite_vendue')
            ->get();
    
        // Formatage des données pour l'API
        $bestSellersFormatted = $bestSellers->map(function ($product) {
            return [
                'id_produit' => $product->id_produit,
                'nom' => $product->nom,
                'photo' => $product->photo ? asset('media/' . $product->photo) : asset('media/concombre.png'),
                'total_quantite_vendue' => $product->total_quantite_vendue,
                'nombre_commandes' => $product->nombre_commandes,
            ];
        });

        // Retourne la réponse JSON
        return response()->json([
            'success' => true,
            'data' => $bestSellersFormatted
        ]);

        
    }
}

