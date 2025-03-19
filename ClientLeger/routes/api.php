<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CreaCompteController;
use App\Http\Controllers\BestSellerController;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\PanierLigneController;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\PanierController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/reset-password', [PasswordResetController::class, 'sendResetLink']);

Route::post('/create-accompte', [CreaCompteController::class, 'createAccount']);

Route::get('/best-sellers', [BestSellerController::class, 'bestSellers']);

Route::get('/produits', [ProduitsController::class, 'getProduitsByCategory']);

Route::post('/signin-bdd', [SigninController::class, 'signin']);

Route::get('/ingredient', [IngredientController::class, 'getIngredientsByCategory']);

Route::post('/panier-update', [PanierLigneController::class, 'addToCart']);
Route::put('/panier-update/{id_panier_ligne}', [PanierLigneController::class, 'updateCartItem']);
Route::delete('/panier-update/{id_panier_ligne}', [PanierLigneController::class, 'deleteCartItem'])->name('panier.destroy');

Route::get('/panier-bdd/{id_panier}', [PanierController::class, 'getPanier']);