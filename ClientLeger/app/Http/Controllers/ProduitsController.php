<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
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
}