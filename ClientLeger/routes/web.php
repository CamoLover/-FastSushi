<?php

use App\Http\Controllers\CreaCompteController;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;


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