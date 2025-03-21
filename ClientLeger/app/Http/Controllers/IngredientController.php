<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use Illuminate\Http\JsonResponse;

class IngredientController extends Controller
{
    /**
     * Récupérer les ingrédients par catégorie.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getIngredientsByCategory(Request $request): JsonResponse
    {
        $request->validate([
            'type_ingredient' => 'required|string|max:45',
        ]);

        $type = $request->query('type_ingredient');
        
        $ingredients = Ingredient::where('type_ingredient', $type)->get();
        
        return response()->json($ingredients);
    }
    
    /**
     * Récupérer tous les ingrédients.
     *
     * @return JsonResponse
     */
    public function getAllIngredients(): JsonResponse
    {
        $ingredients = Ingredient::all();
        
        return response()->json($ingredients);
    }
}
