<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Panier_ligne;

class PanierController extends Controller
{
    public function getPanier($id_panier)
    {
        // Récupérer les lignes du panier
        $lignes = Panier_ligne::where('id_panier', $id_panier)->get();

        if ($lignes->isEmpty()) {
            return response()->json(['message' => 'Panier vide ou inexistant'], 404);
        }

        // Calcul des totaux
        $total_ht = $lignes->sum(fn($ligne) => $ligne->prix_ht * $ligne->quantite);
        $total_ttc = $lignes->sum(fn($ligne) => $ligne->prix_ttc * $ligne->quantite);
        $tva = $total_ttc - $total_ht;

        return response()->json([
            'id_panier' => $id_panier,
            'produits' => $lignes,
            'total_ht' => round($total_ht, 2),
            'total_ttc' => round($total_ttc, 2),
            'tva' => round($tva, 2)
        ]);
    }
}
