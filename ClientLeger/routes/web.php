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

Route::get('/composition-sushi', function () {
    return view('composition-sushi');
});


Route::get('/signup', function () {
    return view('signup');
});

Route::get('/best-seller', function () {
    return view('hero');
});

Route::post('/ajouter-au-panier', [PanierController::class, 'ajouterAuPanier']);

Route::get('/panier', [PanierController::class, 'voirLePanier'])->name('panier.index');

// Routes pour la gestion du panier
Route::put('/panier/{id_panier_ligne}', [PanierLigneController::class, 'updateCartItem'])->name('panier.update');
Route::delete('/panier/{id_panier_ligne}', [PanierLigneController::class, 'deleteCartItem'])->name('panier.destroy');

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
