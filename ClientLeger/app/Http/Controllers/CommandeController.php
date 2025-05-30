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
            // Log the request for debugging
            \Log::debug('Command creation request:', [
                'request' => $request->all(),
                'has_client_session' => session()->has('client'),
                'session_data' => session()->all(),
                'client_id' => session()->has('client') ? session('client')->id_client : null
            ]);

            // Vérifier si l'utilisateur est connecté
            if (!session()->has('client')) {
                \Log::warning('User not logged in when trying to create an order');
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour passer une commande.'
                ]);
            }

            $client = session('client');
            
            // Récupérer le panier du client
            $panier = Panier::where('id_client', $client->id_client)->first();
            
            if (!$panier) {
                \Log::warning('No cart found for client:', ['client_id' => $client->id_client]);
                return response()->json([
                    'success' => false,
                    'message' => 'Votre panier est vide.'
                ]);
            }

            // Récupérer les lignes du panier
            $lignesPanier = Panier_ligne::where('id_panier', $panier->id_panier)->get();
            
            if ($lignesPanier->isEmpty()) {
                \Log::warning('Empty cart for client:', ['client_id' => $client->id_client, 'cart_id' => $panier->id_panier]);
                return response()->json([
                    'success' => false,
                    'message' => 'Votre panier est vide.'
                ]);
            }

            \Log::debug('Creating order with data:', [
                'client_id' => $client->id_client,
                'cart_total' => $panier->montant_tot,
                'cart_items' => $lignesPanier->count(),
                'items' => $lignesPanier->toArray()
            ]);

            // Wrap everything in a transaction
            return DB::transaction(function() use ($client, $panier, $lignesPanier) {
                try {
                    // Créer la commande
                    $commande = Commande::create([
                        'id_client' => $client->id_client,
                        'date_panier' => now()->format('Y-m-d'),
                        'montant_tot' => $panier->montant_tot,
                        'statut' => 'En attente'
                    ]);

                    // Create mapping to track which commande_ligne corresponds to which panier_ligne
                    $ligneMapping = [];

                    // Créer les lignes de commande
                    foreach ($lignesPanier as $ligne) {
                        $commandeLigne = Commande_ligne::create([
                            'id_commande' => $commande->id_commande,
                            'id_produit' => $ligne->id_produit,
                            'quantite' => $ligne->quantite,
                            'nom' => $ligne->nom,
                            'prix_ht' => $ligne->prix_ht,
                            'prix_ttc' => $ligne->prix_ttc
                        ]);
                        
                        // Store mapping between panier_ligne and commande_ligne
                        $ligneMapping[$ligne->id_panier_ligne] = $commandeLigne->id_commande_ligne;
                    }
                    
                    // Transfer custom ingredients from compo_panier to compo_commande
                    $compoItems = DB::table('compo_paniers')
                        ->whereIn('id_panier_ligne', array_keys($ligneMapping))
                        ->get();
                        
                    \Log::debug('Found custom ingredients to transfer:', [
                        'count' => $compoItems->count(), 
                        'items' => $compoItems->toArray()
                    ]);
                    
                    // Now transfer each ingredient to compo_commande
                    foreach ($compoItems as $compo) {
                        if (isset($ligneMapping[$compo->id_panier_ligne])) {
                            DB::table('compo_commandes')->insert([
                                'id_commande_ligne' => $ligneMapping[$compo->id_panier_ligne],
                                'id_ingredient' => $compo->id_ingredient,
                                'prix' => $compo->prix
                            ]);
                            
                            \Log::debug('Transferred ingredient to order:', [
                                'from_panier_ligne' => $compo->id_panier_ligne,
                                'to_commande_ligne' => $ligneMapping[$compo->id_panier_ligne],
                                'ingredient_id' => $compo->id_ingredient,
                                'price' => $compo->prix
                            ]);
                        }
                    }

                    // Vider le panier (including compo_panier entries)
                    DB::table('compo_paniers')
                        ->whereIn('id_panier_ligne', array_keys($ligneMapping))
                        ->delete();
                        
                    Panier_ligne::where('id_panier', $panier->id_panier)->delete();
                    $panier->montant_tot = 0;
                    $panier->save();

                    \Log::info('Order created successfully:', [
                        'order_id' => $commande->id_commande,
                        'client_id' => $client->id_client
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Commande confirmée avec succès!'
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error during order creation transaction:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    // Re-throw to trigger transaction rollback
                    throw $e;
                }
            });
        } catch (\Exception $e) {
            \Log::error('Failed to create order:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création de la commande: ' . $e->getMessage()
            ]);
        }
    }
} 