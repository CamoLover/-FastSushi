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

    public function index()
    {
        $user = Auth::user(); // Récupérer l'utilisateur connecté

        if (!$user) {
            return redirect('/login')->with('error', 'Vous devez être connecté pour voir votre panier.');
        }

        $panier = Panier::where('id_client', $user->id)->get(); // Récupération du panier
        $total = $panier->sum(fn($item) => $item->produit->prix_ttc * $item->quantite);

        return view('panier', compact('panier', 'total')); // Envoi des données à la vue
    }

    public function voirLePanier() 
    {
        return view('panier', Panier::getPanier(5)); // Envoi des données à la vue
    }


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
