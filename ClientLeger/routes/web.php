<?php

use App\Http\Controllers\BestSellerController;
use App\Http\Controllers\CreaCompteController;
use App\Http\Controllers\PanierController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::post('signup-bdd',[CreaCompteController::class, 'createAccount']);
Route::get('/signup-bdd', [CreaCompteController::class, 'createAccount']);


Route::get('/signup', function () {
    return view('signup');
});

Route::get('/best-seller', function () {
    return view('hero');
});

Route::post('/ajouter-au-panier', [PanierController::class, 'ajouterAuPanier']);
Route::get('/panier', function () {
    return view('panier');
});
Route::get('/panier', function () {
    return view('panier');
});
