<?php

use App\Http\Controllers\BestSellerController;
use App\Http\Controllers\CreaCompteController;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\PanierLigneController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ProduitController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Panier;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sign', function () {
    return view('sign');
});

// Routes de gestion des utilisateurs
Route::post('/signup-bdd', [CreaCompteController::class, 'createAccount']);
Route::post('/signin-bdd', [SigninController::class, 'signin']);
Route::post('/logout', [SigninController::class, 'logout'])->name('logout');



// Routes pour les pages d'information
Route::get('/contact', function () {
    return view('contact');
});
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/about', function () {
    return view('about');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/best-seller', function () {
    return view('hero');
});

Route::post('/ajouter-au-panier', [PanierController::class, 'ajouterAuPanier']);


Route::get('/panier', [PanierController::class, 'voirLePanier'])->name('panier.view');

// Routes pour la gestion du panier
Route::put('/panier/{id_panier_ligne}', [PanierLigneController::class, 'updateCartItem'])->name('panier.update');
Route::delete('/panier/{id_panier_ligne}', [PanierLigneController::class, 'deleteCartItem'])->name('panier.destroy');

// Add API-compatible routes to match the ones in api.php
Route::post('/api/panier-update', [PanierLigneController::class, 'addToCart']);
Route::put('/api/panier-update/{id_panier_ligne}', [PanierLigneController::class, 'updateCartItem']);
Route::delete('/api/panier-update/{id_panier_ligne}', [PanierLigneController::class, 'deleteCartItem']);

Route::post('/commande/create', [CommandeController::class, 'createOrder'])->name('commande.create');

// Route for setting flash messages via AJAX
Route::post('/set-flash-message', function(Request $request) {
    if ($request->has('type') && $request->has('message')) {
        $request->session()->flash($request->type, $request->message);
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 400);
});

// Route for user profile
Route::get('/profil', function () {
    if (!session()->has('client')) {
        return redirect('/sign')->with('error', 'Veuillez vous connecter pour accéder à votre profil.');
    }
    return view('profil');
})->name('profil');

Route::get('/api/carousel-products', [BestSellerController::class, 'getCarouselProducts']);

// Add a test route to check cookie functionality
Route::get('/test-cookie', function () {
    $response = response('Testing cookies')
        ->withCookie(cookie('test_cookie_manual', 'test_value', 60));

    return $response;
});

Route::get('/check-cookies', function () {
    $cookies = request()->cookies->all();
    $output = '<h1>Cookies found</h1><pre>' . json_encode($cookies, JSON_PRETTY_PRINT) . '</pre>';

    return response($output);
});

// Add a route to clear cookies for testing
Route::get('/clear-cookies', function () {
    $response = response('Cookies cleared');
    $cookies = request()->cookies->all();

    foreach ($cookies as $name => $value) {
        $response->withCookie(cookie($name, '', -1));
    }

    return $response;
});

// Add a diagnostic route to check the panier cookie
Route::get('/check-panier', function () {
    $cookieValue = request()->cookie('panier');
    $decodedCookie = null;
    $error = null;

    // Try to decode the cookie
    if ($cookieValue) {
        try {
            $decodedCookie = json_decode($cookieValue, true);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
    }

    // Prepare output
    $output = '<h1>Panier Cookie Information</h1>';
    $output .= '<h2>Raw Cookie Value</h2>';
    $output .= '<pre>' . htmlspecialchars($cookieValue ?? 'No cookie found') . '</pre>';

    if ($error) {
        $output .= '<h2>Error</h2>';
        $output .= '<p>' . htmlspecialchars($error) . '</p>';
    }

    if ($decodedCookie) {
        $output .= '<h2>Decoded Contents</h2>';
        $output .= '<pre>' . json_encode($decodedCookie, JSON_PRETTY_PRINT) . '</pre>';

        $output .= '<h2>Items in Cart</h2>';
        $output .= '<ul>';
        foreach ($decodedCookie as $item) {
            $output .= '<li>' . htmlspecialchars($item['nom'] ?? 'Unknown') . ' - Quantity: ' .
                      ($item['quantite'] ?? 0) . ' - Price: ' .
                      ($item['prix_ttc'] ?? 0) . '€</li>';
        }
        $output .= '</ul>';
    }

    // Add information about all cookies
    $output .= '<h2>All Cookies</h2>';
    $output .= '<ul>';
    foreach (request()->cookies->all() as $name => $value) {
        $output .= '<li>' . htmlspecialchars($name) . ': ' .
                  (strlen($value) > 50 ? substr(htmlspecialchars($value), 0, 50) . '...' : htmlspecialchars($value)) .
                  ' (length: ' . strlen($value) . ')</li>';
    }
    $output .= '</ul>';

    return response($output);
});

// Add product-specific route for adding to cart
Route::post('/panier/{id_produit}', [PanierLigneController::class, 'addToCart'])->name('panier.add');
Route::post('/api/panier/{id_produit}', [PanierLigneController::class, 'addToCart'])->name('api.panier.add');

// Add a mock route for direct cart testing (no database needed)
Route::post('/mock-add-to-cart', function(Request $request) {
    $data = $request->all();

    // Validate basic fields
    $id_produit = (int) ($data['id_produit'] ?? 0);
    $quantite = (int) ($data['quantite'] ?? 1);
    $nom = $data['nom'] ?? 'Produit sans nom';
    $prix_ht = (float) ($data['prix_ht'] ?? 0);
    $prix_ttc = (float) ($data['prix_ttc'] ?? 0);

    if (!$id_produit) {
        return response()->json([
            'error' => 'Invalid product ID'
        ], 400);
    }

    // Check if user is logged in - if yes, use the database
    $client = session('client');
    if ($client) {
        // Find the product
        $produit = App\Models\Produit::find($id_produit);
        if (!$produit) {
            // If product doesn't exist, create a temporary product object
            $produit = new \stdClass();
            $produit->id_produit = $id_produit;
            $produit->nom = $nom;
            $produit->prix_ht = $prix_ht;
            $produit->prix_ttc = $prix_ttc;
        } else {
            // If we found the product, use its values
            $nom = $produit->nom;
            $prix_ht = $produit->prix_ht;
            $prix_ttc = $produit->prix_ttc;
        }

        // Find or create the cart
        $panier = App\Models\Panier::firstOrCreate(
            ['id_client' => $client->id_client],
            [
                'id_session' => session()->getId(),
                'date_panier' => now(),
                'montant_tot' => 0
            ]
        );

        // Check if the product is already in the cart
        $existingLine = App\Models\Panier_ligne::where('id_panier', $panier->id_panier)
                                              ->where('id_produit', $id_produit)
                                              ->first();

        if ($existingLine) {
            // Update quantity of existing line
            $existingLine->quantite += $quantite;
            $existingLine->save();
        } else {
            // Create a new line
            App\Models\Panier_ligne::create([
                'id_panier' => $panier->id_panier,
                'id_produit' => $id_produit,
                'quantite' => $quantite,
                'nom' => $nom,
                'prix_ht' => $prix_ht,
                'prix_ttc' => $prix_ttc
            ]);
        }

        // Calculate the total cart items
        $totalItems = App\Models\Panier_ligne::where('id_panier', $panier->id_panier)
                                          ->sum('quantite');

        return response()->json([
            'success' => true,
            'message' => "$nom ajouté au panier avec succès",
            'count' => $totalItems,
            'db_used' => true
        ]);
    }

    // For non-logged in users, use cookies
    $rawCookie = $request->cookie('panier');

    // Log for debugging
    \Log::debug('Raw panier cookie:', ['value' => $rawCookie]);

    // Explicitly create a new array each time
    $cookieCart = [];

    // If we have a cookie, parse it
    if (!empty($rawCookie)) {
        try {
            $decoded = json_decode($rawCookie, true);
            if (is_array($decoded)) {
                // Create a brand new array
                $cookieCart = array_values($decoded);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to decode cookie:', ['error' => $e->getMessage()]);
            $cookieCart = [];
        }
    }

    \Log::debug('Existing cart before adding:', ['cart' => $cookieCart]);

    // Create the new item to add
    $newItem = [
        'id_produit' => $id_produit,
        'nom' => $nom,
        'quantite' => $quantite,
        'prix_ht' => $prix_ht,
        'prix_ttc' => $prix_ttc,
        'id_panier_ligne' => count($cookieCart) // Add a numeric index
    ];

    // Simply append the new item to the end
    $cookieCart[] = $newItem;

    \Log::debug('Cart after adding:', ['cart' => $cookieCart]);

    // Calculate total
    $total = 0;
    $totalItems = 0;
    foreach ($cookieCart as $item) {
        $total += ($item['prix_ttc'] ?? 0) * ($item['quantite'] ?? 0);
        $totalItems += ($item['quantite'] ?? 0);
    }

    // Create a fresh cookie with the updated cart
    $cookie = cookie(
        'panier',
        json_encode($cookieCart),
        10080, // 7 days
        '/',
        null,
        false,
        false // This parameter controls encryption - false means not encrypted
    );

    return response()->json([
        'success' => true,
        'message' => "$nom ajouté au panier avec succès",
        'count' => $totalItems,
        'cart_count' => count($cookieCart),
        'cart_items' => $cookieCart,
        'montant_total' => $total,
        'cookie_used' => true
    ])->withCookie($cookie);
});

// Add a super basic cart handler as backup in case the other route fails
Route::post('/basic-add-to-cart', function(Request $request) {
    $data = $request->all();

    // Validate basic fields
    $id_produit = (int) ($data['id_produit'] ?? 0);
    $quantite = (int) ($data['quantite'] ?? 1);
    $nom = $data['nom'] ?? 'Produit sans nom';
    $prix_ht = (float) ($data['prix_ht'] ?? 0);
    $prix_ttc = (float) ($data['prix_ttc'] ?? 0);

    if (!$id_produit) {
        return response()->json([
            'error' => 'Invalid product ID'
        ], 400);
    }

    // Check if user is logged in
    $client = session('client');
    if ($client) {
        // For logged-in users, add directly to database
        // Create or find user's cart
        $panier = App\Models\Panier::firstOrCreate(
            ['id_client' => $client->id_client],
            [
                'id_session' => session()->getId(),
                'date_panier' => now(),
                'montant_tot' => 0
            ]
        );

        // Create or update cart line
        $existingLine = App\Models\Panier_ligne::where('id_panier', $panier->id_panier)
                                              ->where('id_produit', $id_produit)
                                              ->first();

        if ($existingLine) {
            $existingLine->quantite += $quantite;
            $existingLine->save();
        } else {
            App\Models\Panier_ligne::create([
                'id_panier' => $panier->id_panier,
                'id_produit' => $id_produit,
                'quantite' => $quantite,
                'nom' => $nom,
                'prix_ht' => $prix_ht,
                'prix_ttc' => $prix_ttc
            ]);
        }

        // Get total cart items
        $totalCount = App\Models\Panier_ligne::where('id_panier', $panier->id_panier)
                                           ->sum('quantite');

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté au panier',
            'count' => $totalCount
        ]);
    }

    // For non-logged in users, use cookie
    $cookieCart = [];
    $rawCookie = $request->cookie('panier');
    if (!empty($rawCookie)) {
        try {
            $cookieCart = json_decode($rawCookie, true) ?? [];
        } catch (\Exception $e) {
            $cookieCart = [];
        }
    }

    // Simply add the new item to the end of the array
    $cookieCart[] = [
        'id_produit' => $id_produit,
        'nom' => $nom,
        'quantite' => $quantite,
        'prix_ht' => $prix_ht,
        'prix_ttc' => $prix_ttc,
        'id_panier_ligne' => count($cookieCart)
    ];

    // Calculate totals
    $totalItems = 0;
    foreach ($cookieCart as $item) {
        $totalItems += ($item['quantite'] ?? 0);
    }

    // Create cookie
    $response = response()->json([
        'success' => true,
        'message' => "$nom ajouté au panier",
        'count' => $totalItems,
        'cart_count' => count($cookieCart)
    ]);

    // Set cookie manually
    $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie(
        'panier',
        json_encode($cookieCart),
        time() + (10080 * 60), // 7 days in seconds
        '/',
        null,
        false,
        false
    ));

    return $response;
});

// Add a route for custom sushi orders
Route::post('/simple-add-to-cart', [PanierLigneController::class, 'addCustomToCart']);
Route::post('/regular-add-to-cart', [PanierLigneController::class, 'addRegularToCart']);

Route::get('/menu', [App\Http\Controllers\ProduitsController::class, 'menu']);

Route::get('/compo', function () {
    $customisations = App\Models\Produit::where('type_produit', 'Customisation')->get();
    $ingredients = App\Models\Ingredient::all();
    
    return view('module.composition', compact('customisations', 'ingredients'));
});

// Ajout de la route fallback pour gérer toutes les routes non attribuées
Route::fallback(function () {
    return response()->view('404', [], 404);
});
