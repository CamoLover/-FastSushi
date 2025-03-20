<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Panier_ligne;

use App\Models\PanierLigne;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

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

    // Helper method to add an item to a panier (used when converting cookie to DB)
    protected function addItemToPanier($item, $panier, $clientId)
    {
        // If $item is not an array or doesn't have required fields, return
        if (!is_array($item) || !isset($item['id_produit'])) {
            \Log::error('Invalid item data:', ['item' => $item]);
            return false;
        }
        
        // Check if this is a custom item
        $isCustomItem = isset($item['is_custom']) && $item['is_custom'] === true;
        
        // Try to find the product
        $produit = Produit::find($item['id_produit']);
        
        // Set default values if product not found
        $productData = [
            'nom' => $item['nom'] ?? 'Produit inconnu',
            'prix_ht' => $item['prix_ht'] ?? 0,
            'prix_ttc' => $item['prix_ttc'] ?? 0,
            'quantite' => $item['quantite'] ?? 1
        ];
        
        // If product exists, use its data
        if ($produit) {
            $productData['nom'] = $isCustomItem ? ($produit->nom . ' personnalisé') : $produit->nom;
            
            if (!$isCustomItem) {
                $productData['prix_ht'] = $produit->prix_ht;
                $productData['prix_ttc'] = $produit->prix_ttc;
            }
        }
        
        // For custom items, always create a new entry (no stacking)
        if ($isCustomItem) {
            // Create new line for custom item
            $newLine = Panier_ligne::create([
                'id_panier' => $panier->id_panier,
                'id_produit' => $item['id_produit'],
                'quantite' => 1, // Custom items always have quantity of 1
                'nom' => $productData['nom'],
                'prix_ht' => $productData['prix_ht'],
                'prix_ttc' => $productData['prix_ttc']
            ]);
            
            // If the item has ingredients, add them to compo_paniers
            if (isset($item['ingredients']) && is_array($item['ingredients']) && !empty($item['ingredients'])) {
                foreach ($item['ingredients'] as $ingredient) {
                    DB::table('compo_paniers')->insert([
                        'id_panier_ligne' => $newLine->id_panier_ligne,
                        'id_ingredient' => $ingredient['id'],
                        'prix' => $ingredient['price']
                    ]);
                }
            }
        } else {
            // Regular product - find or create a new line
            $existingLine = Panier_ligne::where('id_panier', $panier->id_panier)
                                       ->where('id_produit', $item['id_produit'])
                                       ->first();
                                       
            if ($existingLine) {
                // Update existing line
                $existingLine->quantite += $productData['quantite'];
                $existingLine->save();
            } else {
                // Create new line
                Panier_ligne::create([
                    'id_panier' => $panier->id_panier,
                    'id_produit' => $item['id_produit'],
                    'quantite' => $productData['quantite'],
                    'nom' => $productData['nom'],
                    'prix_ht' => $productData['prix_ht'],
                    'prix_ttc' => $productData['prix_ttc']
                ]);
            }
        }
        
        return true;
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

    public function voirLePanier(Request $request)
    {
        // Check if user is logged in via session
        $client = session('client');
        
        // Add debug output for all cookies
        \Log::debug('All request cookies:', [
            'cookies' => $request->cookies->all(),
            'has_panier' => $request->hasCookie('panier'),
            'panier_value' => $request->cookie('panier')
        ]);
        
        if ($client) {
            try {
                // Logged in user - get cart from database
                $result = Panier::getPanier($client->id_client);
                
                // If the user has a panier cookie, merge it with the database panier
                if ($request->hasCookie('panier')) {
                    $cookieCart = json_decode($request->cookie('panier'), true);
                    if ($cookieCart && is_array($cookieCart) && count($cookieCart) > 0) {
                        // Load the user's cart from database
                        $userPanier = Panier::firstOrCreate(
                            ['id_client' => $client->id_client],
                            [
                                'id_session' => session()->getId(),
                                'date_panier' => now(),
                                'montant_tot' => 0
                            ]
                        );
                        
                        // For each item in the cookie cart, add it to the database cart
                        foreach ($cookieCart as $item) {
                            $this->addItemToPanier($item, $userPanier, $client->id_client);
                        }
                        
                        // Clear the cookie after merging
                        Cookie::queue(Cookie::forget('panier'));
                        
                        // Refresh the result with the merged cart
                        $result = Panier::getPanier($client->id_client);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error in voirLePanier for logged user:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // If there's an error, return an empty cart view
                $result = [
                    'panier' => [
                        (object)[
                            'lignes' => collect() 
                        ]
                    ],
                    'total_ht' => 0,
                    'total' => 0
                ];
            }
            
            return view('panier', $result);
        } else {
            // Not logged in - use cookie cart
            $cookieCart = [];
            $panierCookie = $request->cookie('panier');
            
            \Log::debug('Cookie cart raw:', [
                'cookie_value' => $panierCookie
            ]);
            
            if (!empty($panierCookie)) {
                try {
                    $cookieCart = json_decode($panierCookie, true);
                    \Log::debug('Parsed cookie cart:', [
                        'parsed' => $cookieCart,
                        'is_array' => is_array($cookieCart),
                        'count' => is_array($cookieCart) ? count($cookieCart) : 0
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error parsing panier cookie:', [
                        'error' => $e->getMessage(),
                        'cookie' => $panierCookie
                    ]);
                }
            } else {
                \Log::debug('No panier cookie found in request');
                
                // Try to get it directly from $_COOKIE
                if (isset($_COOKIE['panier'])) {
                    try {
                        $cookieCart = json_decode($_COOKIE['panier'], true);
                        \Log::debug('Parsed cookie from $_COOKIE:', [
                            'parsed' => $cookieCart,
                            'is_array' => is_array($cookieCart),
                            'count' => is_array($cookieCart) ? count($cookieCart) : 0
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Error parsing panier from $_COOKIE:', [
                            'error' => $e->getMessage(),
                            'cookie' => $_COOKIE['panier']
                        ]);
                    }
                }
                
                // Try to get directly from headers
                $headers = $request->headers->all();
                $cookieHeader = $request->headers->get('cookie');
                \Log::debug('Cookie header:', [
                    'cookie_header' => $cookieHeader
                ]);
                
                // Try to use the direct panier cookie
                if (isset($_COOKIE['panier_direct'])) {
                    try {
                        $decodedCookie = urldecode($_COOKIE['panier_direct']);
                        $cookieCart = json_decode($decodedCookie, true);
                        \Log::debug('Using direct panier cookie:', [
                            'value' => $decodedCookie,
                            'parsed' => $cookieCart
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Error parsing direct panier:', [
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
            
            // Ensure cookieCart is an array
            if (!is_array($cookieCart)) {
                $cookieCart = [];
                \Log::warning('Cookie cart is not an array, using empty array instead');
            }
            
            $total = 0;
            $total_ht = 0;
            
            // Calculate totals
            foreach ($cookieCart as $item) {
                $total += floatval($item['prix_ttc'] ?? 0) * intval($item['quantite'] ?? 0);
                $total_ht += floatval($item['prix_ht'] ?? 0) * intval($item['quantite'] ?? 0);
            }
            
            \Log::debug('Cart totals calculated:', [
                'total' => $total,
                'total_ht' => $total_ht,
                'items_count' => count($cookieCart)
            ]);
            
            // For custom items, we never want to consolidate them
            $normalItems = [];
            $customItems = [];
            
            foreach ($cookieCart as $item) {
                // Check if this is a custom item (has custom ingredients)
                $isCustom = isset($item['is_custom']) && $item['is_custom'] === true;
                
                if ($isCustom) {
                    // Custom items are never consolidated
                    $customItems[] = $item;
                } else {
                    $productId = $item['id_produit'] ?? 0;
                    if (!isset($normalItems[$productId])) {
                        $normalItems[$productId] = $item;
                    } else {
                        // If product already exists, just increase the quantity
                        $normalItems[$productId]['quantite'] += $item['quantite'];
                    }
                }
            }
            
            // Create a numerically indexed version for the view and for cookies
            $newCookieCart = [];
            
            // First add normal items
            foreach ($normalItems as $productId => $item) {
                // Add the id_panier_ligne so we can find it by this value in our controller
                $item['id_panier_ligne'] = count($newCookieCart); // Sequential ID
                $newCookieCart[] = $item;
            }
            
            // Then add custom items
            foreach ($customItems as $item) {
                $item['id_panier_ligne'] = count($newCookieCart); // Sequential ID
                $newCookieCart[] = $item;
            }
            
            // Save the consolidated cart back to cookie
            $cookie = cookie(
                'panier',                      // name
                json_encode($newCookieCart),   // value
                10080,                         // minutes
                '/',                           // path
                null,                          // domain (null = current domain)
                false,                         // secure
                false                          // httpOnly
            );
            
            // Log the cookie creation for debugging
            \Log::debug('Creating cookie in PanierController:', [
                'name' => 'panier',
                'value_length' => strlen(json_encode($newCookieCart)),
                'items_count' => count($newCookieCart),
                'expires' => '+10080 minutes'
            ]);
            
            // Then convert to the lignes collection
            $lignes = collect();
            foreach ($newCookieCart as $index => $item) {
                // Check if this is a custom item with ingredients
                $isCustom = isset($item['is_custom']) && $item['is_custom'] === true;
                
                $ligne = (object)[
                    'id_panier_ligne' => $index,
                    'id_produit' => $item['id_produit'] ?? 0,
                    'nom' => $item['nom'] ?? 'Produit inconnu',
                    'prix_ht' => $item['prix_ht'] ?? 0,
                    'prix_ttc' => $item['prix_ttc'] ?? 0, 
                    'quantite' => $item['quantite'] ?? 0,
                    'produit' => (object)[
                        'type_produit' => 'Plats', // Default to Plats, will update below if we find product or it's custom
                        'photo' => '/media/concombre.png' // Default image
                    ]
                ];
                
                // If it's a custom item, explicitly mark it
                if ($isCustom) {
                    $ligne->produit->type_produit = 'Customisation';
                    $ligne->is_custom = true;
                }
                
                // For custom items, store the ingredients in the object for easy access in the view
                if ($isCustom && isset($item['ingredients']) && is_array($item['ingredients'])) {
                    $ligne->ingredients = $item['ingredients'];
                    \Log::debug('Added ingredients to ligne object for custom item:', [
                        'id_panier_ligne' => $index,
                        'ingredient_count' => count($item['ingredients'])
                    ]);
                }
                
                // Try to get actual product data
                try {
                    $produit = Produit::find($item['id_produit']);
                    if ($produit) {
                        if (!$isCustom) {
                            $ligne->produit->type_produit = $produit->type_produit;
                        }
                        // Fix photo path if needed
                        $photoPath = $produit->photo;
                        if ($photoPath && substr($photoPath, 0, 1) !== '/') {
                            $photoPath = '/media/' . $photoPath;
                        }
                        $ligne->produit->photo = $photoPath;
                    }
                } catch (\Exception $e) {
                    \Log::error('Error getting product:', [
                        'id' => $item['id_produit'],
                        'error' => $e->getMessage()
                    ]);
                    
                    // Try with a direct DB query as a fallback
                    try {
                        $produit = DB::table('produits')->where('id_produit', $item['id_produit'])->first();
                        if ($produit) {
                            if (!$isCustom) {
                                $ligne->produit->type_produit = $produit->type_produit;
                            }
                            // Fix photo path in the fallback too
                            $photoPath = $produit->photo;
                            if ($photoPath && substr($photoPath, 0, 1) !== '/') {
                                $photoPath = '/media/' . $photoPath;
                            }
                            $ligne->produit->photo = $photoPath;
                        }
                    } catch (\Exception $e2) {
                        \Log::error('Error getting product via DB:', [
                            'id' => $item['id_produit'],
                            'error' => $e2->getMessage()
                        ]);
                    }
                }
                
                $lignes->push($ligne);
            }
            
            $result = [
                'panier' => [
                    (object)[
                        'lignes' => $lignes
                    ]
                ],
                'total_ht' => $total_ht,
                'total' => $total
            ];
            
            \Log::debug('Final result structure:', [
                'has_lignes' => isset($result['panier'][0]->lignes),
                'lignes_count' => isset($result['panier'][0]->lignes) ? $result['panier'][0]->lignes->count() : 0,
                'total' => $result['total']
            ]);
            
            return view('panier', $result)->withCookie($cookie);
        }
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
    
    /**
     * Convertit le panier cookie en panier base de données lorsqu'un utilisateur se connecte
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertCookieCartToDatabase(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        $client = session('client');
        
        if (!$client) {
            return response()->json(['error' => 'Utilisateur non connecté'], 401);
        }
        
        // Récupérer le panier stocké dans le cookie
        $cookieCart = json_decode($request->cookie('panier'), true) ?? [];
        
        if (empty($cookieCart)) {
            return response()->json(['message' => 'Aucun panier temporaire à convertir']);
        }
        
        // Chercher ou créer un panier pour ce client
        $panier = Panier::firstOrCreate(
            ['id_client' => $client->id_client],
            [
                'id_session' => session()->getId(),
                'date_panier' => now(),
                'montant_tot' => 0
            ]
        );
        
        // Transférer les éléments du cookie vers la base de données
        foreach ($cookieCart as $item) {
            $this->addItemToPanier($item, $panier, $client->id_client);
        }
        
        // Recalculer le montant total du panier
        $montantTotal = $panier->panier_lignes->sum(function ($ligne) {
            return $ligne->prix_ttc * $ligne->quantite;
        });
        
        // Mise à jour du montant total du panier
        $panier->montant_tot = $montantTotal;
        $panier->save();
        
        // Vider le cookie du panier
        $cookie = cookie('panier', '', -1);
        
        return response()->json([
            'message' => 'Panier temporaire converti avec succès',
            'panier_id' => $panier->id_panier
        ])->withCookie($cookie);
    }
}
