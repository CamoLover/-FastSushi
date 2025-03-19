<?php

use App\Http\Controllers\BestSellerController;
use App\Http\Controllers\CreaCompteController;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PanierController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Panier;

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

Route::get('/panier', [PanierController::class, 'voirLePanier'])->name('panier.index');
/*
Route::put('/panier/{id}', [PanierController::class, 'update'])->name('panier.update');
Route::delete('/panier/{id}', [PanierController::class, 'destroy'])->name('panier.destroy');*/


Route::get('/menu', function () {
    return view('menu');
});