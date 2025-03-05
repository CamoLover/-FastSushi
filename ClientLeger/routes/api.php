<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CreaCompteController;
use App\Http\Controllers\BestSellerController;
use App\Http\Controllers\ProduitsController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/reset-password', [PasswordResetController::class, 'sendResetLink']);

Route::post('/create-accompte', [CreaCompteController::class, 'createAccount']);



Route::get('/best-sellers', [BestSellerController::class, 'bestSellers']);



Route::get('/produits', [ProduitsController::class, 'getProduitsByCategory']);

