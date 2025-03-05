<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Panier_ligne;
use App\Models\PanierLigne;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    public function ajouterAuPanier(Request $request)
    {
        $produit = Produit::find($request->id_produit);
        if (!$produit) {
            return response()->json(['message' => 'Produit introuvable'], 404);
        }

        if (Auth::check()) {
            // Utilisateur connecté : on sauvegarde en base de données
            $panier = Panier::firstOrCreate(
                ['id_client' => Auth::id()],
                ['date_panier' => now(), 'montant_tot' => 0]
            );

            $ligne = Panier_ligne::updateOrCreate(
                ['id_panier' => $panier->id_panier, 'id_produit' => $produit->id_produit],
                ['quantite' => $request->quantite, 'nom' => $produit->nom, 'prix_ht' => $produit->prix_ht, 'prix_ttc' => $produit->prix_ttc]
            );

            return response()->json(['message' => 'Produit ajouté au panier', 'panier' => $panier]);
        } else {
            // Utilisateur non connecté : retour au client pour stockage local
            return response()->json(['local' => true, 'produit' => $produit, 'quantite' => $request->quantite]);
        }
    }
}
