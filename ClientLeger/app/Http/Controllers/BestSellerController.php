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
    
    public function getCarouselProducts()
    {
        // Get best sellers based on order quantities
        $bestSellers = Commande_ligne::select(
                'commande_lignes.id_produit',
                'produits.nom',
                'produits.description',
                'produits.prix_ttc',
                'produits.photo',
                DB::raw('SUM(quantite) as total_quantite_vendue')
            )
            ->join('produits', 'commande_lignes.id_produit', '=', 'produits.id_produit')
            ->groupBy('commande_lignes.id_produit', 'produits.nom', 'produits.description', 'produits.prix_ttc', 'produits.photo')
            ->orderByDesc('total_quantite_vendue')
            ->limit(3)
            ->get();
        
        // Format best sellers data
        $bestSellersFormatted = $bestSellers->map(function ($product) {
            return [
                'id_produit' => $product->id_produit,
                'nom' => $product->nom,
                'description' => $product->description,
                'prix_ttc' => $product->prix_ttc,
                'photo' => $product->photo ?: 'concombre.png',
            ];
        });

        // Get latest products with the same model
        $latestProducts = Produit::select(
                'id_produit',
                'nom',
                'description', 
                'prix_ttc',
                'photo'
            )
            ->orderBy('id_produit', 'desc')
            ->limit(3)
            ->get();
        
        // Format latest products in the same way
        $latestProductsFormatted = $latestProducts->map(function ($product) {
            return [
                'id_produit' => $product->id_produit,
                'nom' => $product->nom,
                'description' => $product->description,
                'prix_ttc' => $product->prix_ttc,
                'photo' => $product->photo ?: 'concombre.png',
            ];
        });

        return response()->json([
            'bestSellers' => $bestSellersFormatted,
            'latestProducts' => $latestProductsFormatted
        ]);
    }
}

