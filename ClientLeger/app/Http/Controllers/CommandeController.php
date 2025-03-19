<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\Commande;
use App\Models\Commande_ligne;
use App\Models\Panier_ligne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    public function createOrder(Request $request)
    {
        try {
            // Vérifier si l'utilisateur est connecté
            if (!session()->has('client')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour passer une commande.'
                ]);
            }

            $client = session('client');
            
            // Récupérer le panier du client
            $panier = Panier::where('id_client', $client->id_client)->first();
            
            if (!$panier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre panier est vide.'
                ]);
            }

            // Récupérer les lignes du panier
            $lignesPanier = Panier_ligne::where('id_panier', $panier->id_panier)->get();
            
            if ($lignesPanier->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre panier est vide.'
                ]);
            }

            // Créer la commande
            $commande = Commande::create([
                'id_client' => $client->id_client,
                'date_panier' => now()->format('Y-m-d'),
                'montant_tot' => $panier->montant_tot,
                'statut' => 'En attente'
            ]);

            // Créer les lignes de commande
            foreach ($lignesPanier as $ligne) {
                Commande_ligne::create([
                    'id_commande' => $commande->id_commande,
                    'id_produit' => $ligne->id_produit,
                    'quantite' => $ligne->quantite,
                    'nom' => $ligne->nom,
                    'prix_ht' => $ligne->prix_ht,
                    'prix_ttc' => $ligne->prix_ttc
                ]);
            }

            // Vider le panier
            Panier_ligne::where('id_panier', $panier->id_panier)->delete();
            $panier->montant_tot = 0;
            $panier->save();

            return response()->json([
                'success' => true,
                'message' => 'Commande confirmée avec succès!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création de la commande: ' . $e->getMessage()
            ]);
        }
    }
} 