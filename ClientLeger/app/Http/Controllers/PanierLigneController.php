<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Panier_ligne;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PanierLigneController extends Controller
{


    // Ajouter un élément au panier
    public function addToCart(Request $request, $id_produit = null)
    {
        // Log the request for debugging
        Log::debug('Panier-update request data:', $request->all());
        
        // Handle both old and new route formats
        if ($id_produit === null) {
            // Old format - id_produit comes from request body
            $data = [
                'id_produit' => (int) $request->input('id_produit'),
                'quantite' => (int) ($request->input('quantite') ?: 1),
                'nom' => $request->input('nom', 'Produit sans nom'),
                'prix_ht' => (float) ($request->input('prix_ht') ?: 0),
                'prix_ttc' => (float) ($request->input('prix_ttc') ?: 0),
            ];
        } else {
            // New format - id_produit comes from route parameter
            $data = [
                'id_produit' => (int) $id_produit,
                'quantite' => (int) ($request->input('quantite') ?: 1),
                'nom' => $request->input('nom', 'Produit sans nom'),
                'prix_ht' => (float) ($request->input('prix_ht') ?: 0),
                'prix_ttc' => (float) ($request->input('prix_ttc') ?: 0),
            ];
        }
        
        // If prix_ht is missing but we have prix_ttc, calculate a default value (with 10% TVA)
        if ($data['prix_ht'] == 0 && $data['prix_ttc'] > 0) {
            $data['prix_ht'] = round($data['prix_ttc'] / 1.1, 4);
        }
        
        // Validation after setting defaults
        $validator = Validator::make($data, [
            'id_produit' => 'required|integer|min:1',
            'quantite'   => 'required|integer|min:1',
            'nom'        => 'required|string|max:100',
            'prix_ht'    => 'required|numeric|min:0',
            'prix_ttc'   => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Log::debug('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        // Verify if the product exists
        $produit = Produit::find($data['id_produit']);
        if (!$produit) {
            Log::debug('Product not found:', ['id' => $data['id_produit']]);
            
            // If product not found but we have valid data, create a temporary product object
            $tmpProduit = new \stdClass();
            $tmpProduit->id_produit = $data['id_produit'];
            $tmpProduit->nom = $data['nom'];
            $tmpProduit->prix_ht = $data['prix_ht'];
            $tmpProduit->prix_ttc = $data['prix_ttc'];
            
            $produit = $tmpProduit;
        } else {
            // If we found the product, update our data with accurate values from DB
            $data['nom'] = $produit->nom;
            $data['prix_ht'] = $produit->prix_ht;
            $data['prix_ttc'] = $produit->prix_ttc;
        }
        
        // Explicit check for logged-in status via session
        $client = session('client');

        if ($client) {
            // User is logged in - store in database
            Log::debug('User is logged in, storing in database:', ['client_id' => $client->id_client]);
            
            // Find or create a cart for this client
            $panier = Panier::firstOrCreate(
                ['id_client' => $client->id_client],
                [
                    'id_session' => session()->getId(),
                    'date_panier' => now(), 
                    'montant_tot' => 0
                ]
            );
            
            // Check if the product already exists in the cart
            $existingLine = Panier_ligne::where('id_panier', $panier->id_panier)
                                      ->where('id_produit', $data['id_produit'])
                                      ->first();

            if ($existingLine) {
                // If the product already exists, update the quantity
                $existingLine->quantite += $data['quantite'];
                $existingLine->save();
                
                Log::debug('Updated existing cart line:', [
                    'product_id' => $data['id_produit'],
                    'new_quantity' => $existingLine->quantite
                ]);
            } else {
                // Otherwise, create a new cart line
                $newLine = Panier_ligne::create([
                    'id_panier'  => $panier->id_panier,
                    'id_produit' => $data['id_produit'],
                    'quantite'   => $data['quantite'],
                    'nom'        => $data['nom'],
                    'prix_ht'    => $data['prix_ht'],
                    'prix_ttc'   => $data['prix_ttc'],
                ]);
                
                Log::debug('Created new cart line:', [
                    'product_id' => $data['id_produit'],
                    'line_id' => $newLine->id_panier_ligne
                ]);
            }

            // Recalculate the total cart amount
            $lignes = Panier_ligne::where('id_panier', $panier->id_panier)->get();
            $montantTotal = $lignes->sum(function ($ligne) {
                return $ligne->prix_ttc * $ligne->quantite;
            });

            // Update the cart's total amount
            $panier->montant_tot = $montantTotal;
            $panier->save();
            
            Log::debug('Cart updated successfully in database', [
                'cart_id' => $panier->id_panier,
                'total_amount' => $montantTotal,
                'total_items' => $lignes->sum('quantite')
            ]);

            return response()->json([
                'message' => 'Produit ajouté au panier avec succès.',
                'montant_total' => $montantTotal,
                'count' => Panier_ligne::where('id_panier', $panier->id_panier)->sum('quantite'),
                'db_used' => true
            ], 201);
        } else {
            // User is not logged in - store in cookie
            Log::debug('User is not logged in, storing in cookie');
            
            // Get existing cart from cookie
            $cookieCart = [];
            $panierCookie = $request->cookie('panier');
            
            if (!empty($panierCookie)) {
                try {
                    $cookieCart = json_decode($panierCookie, true);
                } catch (\Exception $e) {
                    Log::error('Error parsing panier cookie:', [
                        'error' => $e->getMessage(),
                        'cookie' => $panierCookie
                    ]);
                    $cookieCart = [];
                }
            }
            
            // Ensure cookieCart is an array
            if (!is_array($cookieCart)) {
                $cookieCart = [];
            }
            
            // Create the new item 
            $newItem = [
                'id_produit' => $data['id_produit'],
                'nom' => $data['nom'],
                'quantite' => $data['quantite'],
                'prix_ht' => $data['prix_ht'],
                'prix_ttc' => $data['prix_ttc'],
                'id_panier_ligne' => count($cookieCart) // Sequential ID
            ];
            
            // Simply append the new item to the cart
            $cookieCart[] = $newItem;
            
            // Calculer le montant total et le nombre d'articles
            $total = 0;
            $count = 0;
            foreach ($cookieCart as $item) {
                $total += (float)$item['prix_ttc'] * (int)$item['quantite'];
                $count += (int)$item['quantite'];
            }
            
            Log::debug('Updated cookie cart:', [
                'cart' => $cookieCart,
                'total' => $total,
                'count' => $count
            ]);
            
            // Create a cookie
            $cookie = cookie(
                'panier',
                json_encode($cookieCart),
                10080, // 7 days
                '/',
                null,
                false,
                false
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier avec succès',
                'count' => $count,
                'cookie_used' => true
            ], 201)->cookie($cookie);
        }
    }




    // Mettre à jour un élément du panier
    public function updateCartItem(Request $request, $id_panier_ligne)
    {
        // Log debugging information
        Log::debug('UpdateCartItem called', [
            'id_panier_ligne' => $id_panier_ligne,
            'request' => $request->all(),
            'cookie' => $request->cookie('panier'),
            'is_client' => session()->has('client')
        ]);
        
        // Validation des entrées
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:increment,decrement',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Vérifier si l'utilisateur est connecté
        $client = session('client');

        if ($client) {
            try {
                // Utilisateur connecté - mettre à jour l'élément en base de données
                // Trouver la ligne du panier
                $ligne = Panier_ligne::find($id_panier_ligne);
    
                if (!$ligne) {
                    Log::debug('Ligne not found with id:', ['id_panier_ligne' => $id_panier_ligne]);
                    return response()->json(['error' => 'Ligne de panier introuvable'], 404);
                }
    
                // Modifier la quantité selon l'action (ajout ou diminution)
                if ($request->action === 'increment') {
                    $ligne->quantite++;
                } elseif ($request->action === 'decrement') {
                    $ligne->quantite--;
    
                    // S'assurer que la quantité ne devienne pas négative
                    if ($ligne->quantite < 1) {
                        $ligne->delete();
                        
                        // Récupérer le panier
                        $panier = Panier::find($ligne->id_panier);
                        
                        // Recalculer le montant total du panier - using direct SQL calculation
                        $lignes = Panier_ligne::where('id_panier', $panier->id_panier)->get();
                        $montantTotal = 0;
                        foreach ($lignes as $line) {
                            $montantTotal += $line->prix_ttc * $line->quantite;
                        }
                        
                        // Mise à jour du montant total du panier
                        $panier->montant_tot = $montantTotal;
                        $panier->save();
                        
                        return response()->json([
                            'message' => 'Produit supprimé du panier.',
                            'montant_total' => $montantTotal,
                            'count' => $lignes->sum('quantite')
                        ], 200);
                    }
                }
    
                $ligne->save();
    
                // Récupérer le panier
                $panier = Panier::find($ligne->id_panier);
                
                // Recalculer le montant total du panier - using direct SQL calculation
                $lignes = Panier_ligne::where('id_panier', $panier->id_panier)->get();
                $montantTotal = 0;
                foreach ($lignes as $line) {
                    $montantTotal += $line->prix_ttc * $line->quantite;
                }
    
                // Mise à jour du montant total du panier
                $panier->montant_tot = $montantTotal;
                $panier->save();
    
                // Récupérer les données mises à jour pour la vue
                $result = Panier::getPanier($panier->id_client);
                $html = view('_panier', $result)->render();
    
                return response()->json([
                    'message' => 'Quantité mise à jour avec succès.',
                    'nouvelle_quantite' => $ligne->quantite,
                    'montant_total' => $montantTotal,
                    'html' => $html,
                    'count' => Panier_ligne::where('id_panier', $panier->id_panier)->sum('quantite')
                ], 200);
            } catch (\Exception $e) {
                Log::error('Error in updateCartItem for logged user:', [
                    'error' => $e->getMessage(), 
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['error' => 'Erreur lors de la mise à jour du panier: ' . $e->getMessage()], 500);
            }
        } else {
            try {
                // Utilisateur non connecté - mettre à jour le cookie
                $cookieCart = [];
                $panierCookie = $request->cookie('panier');
                
                if (!empty($panierCookie)) {
                    try {
                        $cookieCart = json_decode($panierCookie, true);
                    } catch (\Exception $e) {
                        Log::error('Error parsing cookie:', ['error' => $e->getMessage()]);
                        $cookieCart = [];
                    }
                }
                
                // Ensure cookieCart is an array
                if (!is_array($cookieCart)) {
                    $cookieCart = [];
                    return response()->json(['error' => 'Panier vide'], 404);
                }
                
                // Debug log for cookie cart structure
                Log::debug('Cookie cart structure:', [
                    'cookie_cart' => $cookieCart,
                    'id_requested' => $id_panier_ligne,
                    'is_numeric' => is_numeric($id_panier_ligne),
                    'as_int' => (int)$id_panier_ligne,
                    'keys' => array_keys($cookieCart)
                ]);
                
                // Instead of using direct array index, we need to find the item with the matching index
                // as it might not be a sequential array
                $found = false;
                $index = 0;
                
                // First, find the item in the cart by id_panier_ligne
                foreach ($cookieCart as $idx => $item) {
                    if ($idx == $id_panier_ligne || 
                        (isset($item['id_panier_ligne']) && $item['id_panier_ligne'] == $id_panier_ligne) ||
                        (isset($item['id_produit']) && $item['id_produit'] == $id_panier_ligne)) {
                        $found = true;
                        $index = $idx;
                        break;
                    }
                }
                
                if (!$found) {
                    return response()->json(['error' => 'Produit introuvable dans le panier'], 404);
                }
                
                // Modifier la quantité selon l'action
                if ($request->action === 'increment') {
                    $cookieCart[$index]['quantite']++;
                } elseif ($request->action === 'decrement') {
                    $cookieCart[$index]['quantite']--;
                    
                    // Supprimer l'article si la quantité est inférieure à 1
                    if ($cookieCart[$index]['quantite'] < 1) {
                        array_splice($cookieCart, $index, 1);
                        // Re-index after deletion
                        $cookieCart = array_values($cookieCart);
                    }
                }
                
                // Re-index to ensure numeric indices
                $cookieCart = array_values($cookieCart);
                
                // Calculer le montant total et le nombre d'articles
                $total = 0;
                $count = 0;
                foreach ($cookieCart as $item) {
                    $total += (float)$item['prix_ttc'] * (int)$item['quantite'];
                    $count += (int)$item['quantite'];
                }
                
                Log::debug('Updated cookie cart:', [
                    'cart' => $cookieCart,
                    'total' => $total,
                    'count' => $count
                ]);
                
                // Update id_panier_ligne values to ensure correct sequential ordering
                foreach ($cookieCart as $i => $item) {
                    $cookieCart[$i]['id_panier_ligne'] = $i;
                }
                
                // Générer le HTML du panier pour les cookies
                $result = [
                    'panier' => [
                        (object)[
                            'lignes' => collect($cookieCart)->map(function($item, $index) {
                                return (object) [
                                    'id_panier_ligne' => $index,
                                    'id_produit' => $item['id_produit'],
                                    'nom' => $item['nom'],
                                    'prix_ht' => $item['prix_ht'],
                                    'prix_ttc' => $item['prix_ttc'],
                                    'quantite' => $item['quantite'],
                                    'produit' => (object) [
                                        'type_produit' => 'Plats', // Default category
                                        'photo' => '/media/concombre.png' // Default image
                                    ]
                                ];
                            })
                        ]
                    ],
                    'total_ht' => $total,
                    'total' => $total
                ];
                
                // Essayer de récupérer plus d'informations sur les produits
                if(isset($result['panier'][0]->lignes) && $result['panier'][0]->lignes->isNotEmpty()) {
                    foreach ($result['panier'][0]->lignes as $ligne) {
                        try {
                            $produit = Produit::find($ligne->id_produit);
                            if ($produit) {
                                $ligne->produit->type_produit = $produit->type_produit;
                                // Fix photo handling
                                $ligne->produit->photo = $produit->photo;
                                $ligne->produit->photo_type = $produit->photo_type;
                            }
                        } catch (\Exception $e) {
                            Log::error('Error finding product:', ['id' => $ligne->id_produit, 'error' => $e->getMessage()]);
                        }
                    }
                }
                
                $html = view('_panier', $result)->render();
                
                // Créer un cookie qui expire dans 7 jours
                $cookie = cookie(
                    'panier',                      // nom du cookie
                    json_encode($cookieCart),      // valeur (encodée en JSON)
                    10080,                         // durée en minutes (7 jours)
                    '/',                           // chemin du cookie (racine du site)
                    null,                          // domaine (null = domaine actuel)
                    false,                         // Secure flag (false = marche en HTTP)
                    false                          // HttpOnly flag (false = accessible en JS)
                );
                
                // Log the cookie creation for debugging
                Log::debug('Creating cookie with settings in updateCartItem:', [
                    'name' => 'panier',
                    'value_length' => strlen(json_encode($cookieCart)),
                    'expires' => '+10080 minutes',
                    'path' => '/',
                    'domain' => 'null (current domain)',
                    'secure' => false,
                    'httpOnly' => false
                ]);
                
                return response()->json([
                    'html' => $html,
                    'message' => 'Panier mis à jour avec succès',
                    'count' => $count,
                    'nouvelle_quantite' => isset($cookieCart[$index]) ? $cookieCart[$index]['quantite'] : 0
                ])->cookie($cookie);
            } catch (\Exception $e) {
                Log::error('Error in updateCartItem for cookie cart:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['error' => 'Erreur lors de la mise à jour du panier: ' . $e->getMessage()], 500);
            }
        }
    }

    // Supprimer un élément du panier
    public function deleteCartItem($id_panier_ligne, Request $request)
    {
        // Log debugging information for delete action
        Log::debug('DeleteCartItem called', [
            'id_panier_ligne' => $id_panier_ligne,
            'request' => $request->all(),
            'cookie' => $request->cookie('panier'),
            'is_client' => session()->has('client')
        ]);
        
        // Vérifier si l'utilisateur est connecté
        $client = session('client');

        if ($client) {
            try {
                // Utilisateur connecté - supprimer l'élément en base de données
                $ligne = Panier_ligne::find($id_panier_ligne);
    
                if (!$ligne) {
                    Log::debug('Cart line not found for delete:', ['id_panier_ligne' => $id_panier_ligne]);
                    return response()->json(['message' => 'Élément non trouvé'], 404);
                }
    
                $panier = $ligne->panier;
                
                // Si le panier n'existe pas, retourner une erreur
                if (!$panier) {
                    return response()->json(['error' => 'Panier introuvable'], 404);
                }
                
                // Récupérer l'ID client avant de supprimer la ligne
                $id_client = $panier->id_client;
                
                $ligne->delete();
    
                // Recalculer le montant total du panier
                $lignes = $panier->panier_lignes;
                $montantTotal = 0;
                foreach ($lignes as $ligne) {
                    $montantTotal += $ligne->prix_ttc * $ligne->quantite;
                }
                $panier->montant_tot = $montantTotal;
                $panier->save();
    
                // Récupérer les données mises à jour pour la vue
                $result = Panier::getPanier($id_client);
                $html = view('_panier', $result)->render();
    
                return response()->json([
                    'html' => $html,
                    'message' => 'Panier mis à jour avec succès',
                    'count' => $lignes->sum('quantite')
                ]);
            } catch (\Exception $e) {
                Log::error('Error in deleteCartItem for logged user:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['error' => 'Erreur lors de la suppression du produit: ' . $e->getMessage()], 500);
            }
        } else {
            try {
                // Utilisateur non connecté - supprimer du cookie
                $cookieCart = [];
                $panierCookie = $request->cookie('panier');
                
                if (!empty($panierCookie)) {
                    try {
                        $cookieCart = json_decode($panierCookie, true);
                    } catch (\Exception $e) {
                        Log::error('Error parsing cookie in delete:', ['error' => $e->getMessage()]);
                        $cookieCart = [];
                    }
                }
                
                // Ensure cookieCart is an array
                if (!is_array($cookieCart)) {
                    $cookieCart = [];
                    return response()->json(['message' => 'Panier vide'], 404);
                }
                
                // Debug log for cookie structure
                Log::debug('Cookie cart structure for delete:', [
                    'cookie_cart' => $cookieCart,
                    'id_requested' => $id_panier_ligne,
                    'is_numeric' => is_numeric($id_panier_ligne),
                    'as_int' => (int)$id_panier_ligne,
                    'keys' => array_keys($cookieCart)
                ]);
                
                // Instead of using direct array index, we need to find the item 
                // as it might not be a sequential array
                $found = false;
                $index = 0;
                
                // Find the item in the cart by id_panier_ligne or id_produit
                foreach ($cookieCart as $idx => $item) {
                    if ($idx == $id_panier_ligne || 
                        (isset($item['id_panier_ligne']) && $item['id_panier_ligne'] == $id_panier_ligne) ||
                        (isset($item['id_produit']) && $item['id_produit'] == $id_panier_ligne)) {
                        $found = true;
                        $index = $idx;
                        break;
                    }
                }
                
                if (!$found) {
                    return response()->json(['message' => 'Élément non trouvé dans le panier'], 404);
                }
                
                // Supprimer l'élément du tableau
                array_splice($cookieCart, $index, 1);
                // Re-index after deletion
                $cookieCart = array_values($cookieCart);
                
                // Update id_panier_ligne values to ensure correct sequential ordering
                foreach ($cookieCart as $i => $item) {
                    $cookieCart[$i]['id_panier_ligne'] = $i;
                }
                
                // Calculer le montant total et le nombre d'articles
                $total = 0;
                $count = 0;
                foreach ($cookieCart as $item) {
                    $total += (float)$item['prix_ttc'] * (int)$item['quantite'];
                    $count += (int)$item['quantite'];
                }
                
                Log::debug('Updated cookie cart:', [
                    'cart' => $cookieCart,
                    'total' => $total,
                    'count' => $count
                ]);
                
                // Générer le HTML du panier pour les cookies
                $result = [
                    'panier' => [
                        (object)[
                            'lignes' => collect($cookieCart)->map(function($item, $index) {
                                return (object) [
                                    'id_panier_ligne' => $index,
                                    'id_produit' => $item['id_produit'],
                                    'nom' => $item['nom'],
                                    'prix_ht' => $item['prix_ht'],
                                    'prix_ttc' => $item['prix_ttc'],
                                    'quantite' => $item['quantite'],
                                    'produit' => (object) [
                                        'type_produit' => 'Plats', // Default category
                                        'photo' => '/media/concombre.png' // Default image
                                    ]
                                ];
                            })
                        ]
                    ],
                    'total_ht' => $total,
                    'total' => $total
                ];
                
                // Essayer de récupérer plus d'informations sur les produits
                if(isset($result['panier'][0]->lignes) && $result['panier'][0]->lignes->isNotEmpty()) {
                    foreach ($result['panier'][0]->lignes as $ligne) {
                        try {
                            $produit = Produit::find($ligne->id_produit);
                            if ($produit) {
                                $ligne->produit->type_produit = $produit->type_produit;
                                // Fix photo handling
                                $ligne->produit->photo = $produit->photo;
                                $ligne->produit->photo_type = $produit->photo_type;
                            }
                        } catch (\Exception $e) {
                            Log::error('Error finding product in delete:', ['id' => $ligne->id_produit, 'error' => $e->getMessage()]);
                        }
                    }
                }
                
                $html = view('_panier', $result)->render();
                
                // Créer un cookie qui expire dans 7 jours
                $cookie = cookie(
                    'panier',                      // nom du cookie
                    json_encode($cookieCart),      // valeur (encodée en JSON)
                    10080,                         // durée en minutes (7 jours)
                    '/',                           // chemin du cookie (racine du site)
                    null,                          // domaine (null = domaine actuel)
                    false,                         // Secure flag (false = marche en HTTP)
                    false                          // HttpOnly flag (false = accessible en JS)
                );
                
                // Log the cookie creation for debugging
                Log::debug('Creating cookie with settings in deleteCartItem:', [
                    'name' => 'panier',
                    'value_length' => strlen(json_encode($cookieCart)),
                    'expires' => '+10080 minutes',
                    'path' => '/',
                    'domain' => 'null (current domain)',
                    'secure' => false,
                    'httpOnly' => false
                ]);
                
                return response()->json([
                    'html' => $html,
                    'message' => 'Panier mis à jour avec succès',
                    'count' => $count
                ])->cookie($cookie);
            } catch (\Exception $e) {
                Log::error('Error in deleteCartItem for cookie cart:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString() 
                ]);
                return response()->json(['error' => 'Erreur lors de la suppression du produit: ' . $e->getMessage()], 500);
            }
        }
    }

    public function addCustomToCart(Request $request)
    {
        Log::debug('Custom sushi order request data:', $request->all());
        
        // Extract data from the request
        $data = [
            'id_produit' => (int) $request->input('id_produit'),
            'nom' => $request->input('nom', 'Produit personnalisé'),
            'prix_ttc' => (float) $request->input('prix_ttc', 0),
            'prix_ht' => (float) $request->input('prix_ht', 0),
            'quantite' => 1, // Custom items always have quantity of 1
            'ingredients' => $request->input('ingredients', [])
        ];
        
        // Log ingredients data for debugging
        Log::debug('Ingredients data:', [
            'ingredients' => $data['ingredients'],
            'type' => gettype($data['ingredients']),
            'is_array' => is_array($data['ingredients']),
            'count' => is_array($data['ingredients']) ? count($data['ingredients']) : 0
        ]);
        
        if (!empty($data['ingredients'])) {
            Log::debug('First ingredient:', [
                'data' => $data['ingredients'][0] ?? 'No first element',
                'keys' => is_array($data['ingredients'][0]) ? array_keys($data['ingredients'][0]) : 'Not an array'
            ]);
        }
        
        // Validate basic fields
        $validator = Validator::make($data, [
            'id_produit' => 'required|integer|min:1',
            'nom' => 'required|string|max:100',
            'prix_ht' => 'required|numeric|min:0',
            'prix_ttc' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Log::debug('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }
        
        // Check for client session
        $client = session('client');
        
        if ($client) {
            // User is logged in - store in database
            Log::debug('User is logged in, storing custom item in database:', ['client_id' => $client->id_client]);
            
            // Find or create a cart for this client
            $panier = Panier::firstOrCreate(
                ['id_client' => $client->id_client],
                [
                    'id_session' => session()->getId(),
                    'date_panier' => now(), 
                    'montant_tot' => 0
                ]
            );
            
            // For custom items, always create a new entry (no stacking)
            $newLine = Panier_ligne::create([
                'id_panier'  => $panier->id_panier,
                'id_produit' => $data['id_produit'],
                'quantite'   => 1,
                'nom'        => $data['nom'],
                'prix_ht'    => $data['prix_ht'],
                'prix_ttc'   => $data['prix_ttc'],
            ]);
            
            Log::debug('Created new cart line for custom item:', [
                'product_id' => $data['id_produit'],
                'line_id' => $newLine->id_panier_ligne
            ]);
            
            // Store ingredients in compo_paniers table
            if (!empty($data['ingredients'])) {
                Log::debug('About to store ingredients, count:', [count($data['ingredients'])]);
                
                foreach ($data['ingredients'] as $ingredient) {
                    Log::debug('Processing ingredient:', [
                        'ingredient' => $ingredient,
                        'type' => gettype($ingredient),
                        'is_array' => is_array($ingredient),
                        'keys' => is_array($ingredient) ? array_keys($ingredient) : 'Not an array'
                    ]);
                    
                    if (is_array($ingredient) && isset($ingredient['id']) && isset($ingredient['price'])) {
                        DB::table('compo_paniers')->insert([
                            'id_panier_ligne' => $newLine->id_panier_ligne,
                            'id_ingredient' => $ingredient['id'],
                            'prix' => $ingredient['price']
                        ]);
                        
                        Log::debug('Inserted ingredient into compo_paniers:', [
                            'id_ingredient' => $ingredient['id'],
                            'prix' => $ingredient['price']
                        ]);
                    } else {
                        Log::warning('Skipping invalid ingredient format:', [
                            'ingredient' => $ingredient
                        ]);
                    }
                }
                
                Log::debug('Added ingredients to compo_paniers for custom item', [
                    'count' => count($data['ingredients'])
                ]);
            } else {
                Log::warning('No ingredients found for custom item');
            }

            // Recalculate the total cart amount
            $lignes = Panier_ligne::where('id_panier', $panier->id_panier)->get();
            $montantTotal = $lignes->sum(function ($ligne) {
                return $ligne->prix_ttc * $ligne->quantite;
            });

            // Update the cart's total amount
            $panier->montant_tot = $montantTotal;
            $panier->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Plat personnalisé ajouté au panier.',
                'montant_total' => $montantTotal,
                'count' => Panier_ligne::where('id_panier', $panier->id_panier)->sum('quantite')
            ], 201);
        } else {
            // User is not logged in - store in cookie
            Log::debug('User is not logged in, storing custom item in cookie');
            
            // Get existing cart from cookie
            $cookieCart = [];
            $panierCookie = $request->cookie('panier');
            
            if (!empty($panierCookie)) {
                try {
                    $cookieCart = json_decode($panierCookie, true);
                } catch (\Exception $e) {
                    Log::error('Error parsing panier cookie:', [
                        'error' => $e->getMessage(),
                        'cookie' => $panierCookie
                    ]);
                    $cookieCart = [];
                }
            }
            
            // Ensure cookieCart is an array
            if (!is_array($cookieCart)) {
                $cookieCart = [];
            }
            
            // Generate a unique id for the cookie cart line
            $id_panier_ligne = count($cookieCart) + 1;
            
            // Create the new custom item with ingredients 
            $newItem = [
                'id_produit' => $data['id_produit'],
                'nom' => $data['nom'],
                'quantite' => 1,
                'prix_ht' => $data['prix_ht'],
                'prix_ttc' => $data['prix_ttc'],
                'id_panier_ligne' => $id_panier_ligne,
                'is_custom' => true,
                'ingredients' => $data['ingredients']
            ];
            
            // Add the new item to the cart
            $cookieCart[] = $newItem;
            
            // Calculate total cart amount and count
            $total = 0;
            $count = 0;
            foreach ($cookieCart as $item) {
                $total += (float)$item['prix_ttc'] * (int)$item['quantite'];
                $count += (int)$item['quantite'];
            }
            
            // Create a cookie
            $cookie = cookie(
                'panier',
                json_encode($cookieCart),
                10080, // 7 days
                '/',
                null,
                false,
                false
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Plat personnalisé ajouté au panier',
                'count' => $count
            ], 201)->cookie($cookie);
        }
    }

    /**
     * Add a regular (non-custom) item to the cart
     */
    public function addRegularToCart(Request $request)
    {
        Log::debug('Regular menu item request data:', $request->all());
        
        // Extract data from the request
        $data = [
            'id_produit' => (int) $request->input('id_produit'),
            'nom' => $request->input('nom', 'Produit inconnu'),
            'prix_ttc' => (float) $request->input('prix_ttc', 0),
            'prix_ht' => (float) $request->input('prix_ht', 0),
            'quantite' => (int) $request->input('quantite', 1)
        ];
        
        // Validate basic fields
        $validator = Validator::make($data, [
            'id_produit' => 'required|integer|min:1',
            'nom' => 'required|string|max:100',
            'prix_ht' => 'required|numeric|min:0',
            'prix_ttc' => 'required|numeric|min:0',
            'quantite' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            Log::debug('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }
        
        // Check for client session
        $client = session('client');
        
        if ($client) {
            // User is logged in - store in database
            Log::debug('User is logged in, storing regular item in database:', ['client_id' => $client->id_client]);
            
            // Find or create a cart for this client
            $panier = Panier::firstOrCreate(
                ['id_client' => $client->id_client],
                [
                    'id_session' => session()->getId(),
                    'date_panier' => now(), 
                    'montant_tot' => 0
                ]
            );
            
            // Check if product already exists in cart
            $existingLine = Panier_ligne::where('id_panier', $panier->id_panier)
                                     ->where('id_produit', $data['id_produit'])
                                     ->first();
            
            if ($existingLine) {
                // Update existing line
                $existingLine->quantite += $data['quantite'];
                $existingLine->save();
                $lineId = $existingLine->id_panier_ligne;
            } else {
                // Create new line
                $newLine = Panier_ligne::create([
                    'id_panier'  => $panier->id_panier,
                    'id_produit' => $data['id_produit'],
                    'quantite'   => $data['quantite'],
                    'nom'        => $data['nom'],
                    'prix_ht'    => $data['prix_ht'],
                    'prix_ttc'   => $data['prix_ttc'],
                ]);
                $lineId = $newLine->id_panier_ligne;
            }
            
            // Recalculate the total cart amount
            $lignes = Panier_ligne::where('id_panier', $panier->id_panier)->get();
            $montantTotal = $lignes->sum(function ($ligne) {
                return $ligne->prix_ttc * $ligne->quantite;
            });

            // Update the cart's total amount
            $panier->montant_tot = $montantTotal;
            $panier->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier.',
                'montant_total' => $montantTotal,
                'count' => Panier_ligne::where('id_panier', $panier->id_panier)->sum('quantite')
            ], 201);
        } else {
            // User is not logged in - store in cookie
            Log::debug('User is not logged in, storing regular item in cookie');
            
            // Get existing cart from cookie
            $cookieCart = [];
            $panierCookie = $request->cookie('panier');
            
            if (!empty($panierCookie)) {
                try {
                    $cookieCart = json_decode($panierCookie, true);
                } catch (\Exception $e) {
                    Log::error('Error parsing panier cookie:', [
                        'error' => $e->getMessage(),
                        'cookie' => $panierCookie
                    ]);
                    $cookieCart = [];
                }
            }
            
            // Ensure cookieCart is an array
            if (!is_array($cookieCart)) {
                $cookieCart = [];
            }
            
            // Check if product already exists in cart
            $existingItemIndex = -1;
            foreach ($cookieCart as $index => $item) {
                if (isset($item['id_produit']) && $item['id_produit'] == $data['id_produit'] && 
                    (!isset($item['is_custom']) || $item['is_custom'] !== true)) {
                    $existingItemIndex = $index;
                    break;
                }
            }
            
            if ($existingItemIndex >= 0) {
                // Update existing item
                $cookieCart[$existingItemIndex]['quantite'] += $data['quantite'];
            } else {
                // Generate a unique id for the cookie cart line
                $id_panier_ligne = count($cookieCart) + 1;
                
                // Create the new item without marking it as custom
                $newItem = [
                    'id_produit' => $data['id_produit'],
                    'nom' => $data['nom'],
                    'quantite' => $data['quantite'],
                    'prix_ht' => $data['prix_ht'],
                    'prix_ttc' => $data['prix_ttc'],
                    'id_panier_ligne' => $id_panier_ligne
                    // Intentionally not setting is_custom flag so it's treated as regular item
                ];
                
                // Add the new item to the cart
                $cookieCart[] = $newItem;
            }
            
            // Calculate total cart amount and count
            $total = 0;
            $count = 0;
            foreach ($cookieCart as $item) {
                $total += (float)$item['prix_ttc'] * (int)$item['quantite'];
                $count += (int)$item['quantite'];
            }
            
            // Create a cookie
            $cookie = cookie(
                'panier',
                json_encode($cookieCart),
                10080, // 7 days
                '/',
                null,
                false,
                false
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier',
                'count' => $count
            ], 201)->cookie($cookie);
        }
    }

}