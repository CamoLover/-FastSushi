<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CreaCompteController;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\IngredientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/reset-password', [PasswordResetController::class, 'sendResetLink']);

Route::post('/create-accompte', [CreaCompteController::class, 'createAccount']);

Route::post('/signin-bdd', [SigninController::class, 'signin']);

Route::get('/ingredient', [IngredientController::class, 'getIngredientsByCategory']);


use App\Http\Controllers\PanierLigneController;

Route::post('/panier-update', [PanierLigneController::class, 'addToCart']);
Route::put('/panier-update/{id_panier_ligne}', [PanierLigneController::class, 'updateCartItem']);
Route::delete('/panier-update/{id_panier_ligne}', [PanierLigneController::class, 'deleteCartItem']);



use App\Http\Controllers\PanierController;

Route::get('/panier-bdd/{id_panier}', [PanierController::class, 'getPanier']);
