<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Panier_ligne;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PanierLigneController extends Controller
{


    // Ajouter un élément au panier
    public function addToCart(Request $request)
    {
        // Validation des entrées
        $validator = Validator::make($request->all(), [
            'id_produit' => 'required|exists:produits,id_produit',
            'quantite'   => 'required|integer|min:1',
            'nom'        => 'required|string|max:45',
            'prix_ht'    => 'required|numeric',
            'prix_ttc'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $produit = Produit::find($request->id_produit);

        // Si l'utilisateur est connecté
        if (Auth::check()) {
            $clientId = Auth::user()->id;

            // Chercher ou créer un panier pour cet utilisateur
            $panier = Panier::firstOrCreate(
                ['id_client' => $clientId],
                ['date_panier' => now(), 'montant_tot' => 0] // Initialisation du panier
            );
        } else {
            // Si l'utilisateur n'est pas connecté, on utilise la session
            $panierId = Session::get('id_panier');
            if (!$panierId) {
                // Créer un panier de session
                $panier = Panier::create([
                    'id_session' => session()->getId(),
                    'date_panier' => now(),
                    'montant_tot' => 0
                ]);
                Session::put('id_panier', $panier->id_panier);
            } else {
                $panier = Panier::find($panierId);
            }
        }

        // Vérifier si le produit existe déjà dans le panier
        $existingLine = Panier_ligne::where('id_panier', $panier->id_panier)
                                ->where('id_produit', $produit->id_produit)
                                ->first();

        if ($existingLine) {
            // Si le produit existe déjà, mettre à jour la quantité
            $existingLine->quantite += $request->quantite;
            $existingLine->save();
        } else {
            // Sinon, créer une nouvelle ligne de panier
            Panier_ligne::create([
                'id_panier'  => $panier->id_panier,
                'id_produit' => $produit->id_produit,
                'quantite'   => $request->quantite,
                'nom'        => $request->nom,
                'prix_ht'    => $request->prix_ht,
                'prix_ttc'   => $request->prix_ttc,
            ]);
        }

        // Recalculer le montant total du panier
        $montantTotal = $panier->panier_lignes->sum(function ($ligne) {
            return $ligne->prix_ttc * $ligne->quantite;
        });

        // Mise à jour du montant total du panier
        $panier->montant_tot = $montantTotal;
        $panier->save();

        return response()->json([
            'message' => 'Produit ajouté au panier avec succès.',
            'montant_total' => $montantTotal
        ], 201);
    }




    // Mettre à jour un élément du panier
    public function updateCartItem(Request $request, $id_panier_ligne)
    {
        // Validation des entrées
        $validator = Validator::make($request->all(), [
            'quantite'   => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Trouver la ligne du panier
        $ligne = Panier_ligne::find($id_panier_ligne);

        if (!$ligne) {
            return response()->json(['error' => 'Ligne de panier introuvable'], 404);
        }

        // Mettre à jour la quantité
        $ligne->quantite = $request->quantite;
        $ligne->save();

        // Recalculer le montant total du panier
        $panier = $ligne->panier;
        $montantTotal = $panier->panier_lignes->sum(function ($ligne) {
            return $ligne->prix_ttc * $ligne->quantite;
        });

        // Mise à jour du montant total du panier
        $panier->montant_tot = $montantTotal;
        $panier->save();

        return response()->json([
            'message' => 'Produit mis à jour dans le panier.',
            'montant_total' => $montantTotal
        ], 200);
    }





    // Supprimer un élément du panier
    public function deleteCartItem($id_panier_ligne)
    {
        $ligne = Panier_ligne::find($id_panier_ligne);

        if (!$ligne) {
            return response()->json(['message' => 'Élément non trouvé'], 404);
        }

        $ligne->delete();

        return response()->json(['message' => 'Élément supprimé du panier']);
    }



}