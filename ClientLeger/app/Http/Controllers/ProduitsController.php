<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Ingredient;
use Illuminate\Http\JsonResponse;

class ProduitsController extends Controller
{
    /**
     * Récupérer les produits par catégorie.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProduitsByCategory(Request $request): JsonResponse
    {
        $request->validate([
            'type_produit' => 'required|string|max:45',
        ]);

        $type = $request->query('type_produit');

        $produits = Produit::where('type_produit', $type)->get();

        return response()->json($produits);
    }
    
    /**
     * Affiche la page du menu avec tous les produits groupés par catégorie.
     *
     * @return \Illuminate\View\View
     */
    public function menu()
    {
        $entrees = Produit::where('type_produit', 'Entrée')->get();
        $soupes = Produit::where('type_produit', 'Soupe')->get();
        $plats = Produit::where('type_produit', 'Plats')->get();
        $customisations = Produit::where('type_produit', 'Customisation')->get();
        $desserts = Produit::where('type_produit', 'Desserts')->get();
        $ingredients = Ingredient::all();
        
        return view('menu', compact('entrees', 'soupes', 'plats', 'customisations', 'desserts', 'ingredients'));
    }
}