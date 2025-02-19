<?php

use App\Http\Controllers\CreaCompteController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/signup', function () {
    return view('signup');
});



Route::post('signup-bdd',[CreaCompteController::class, 'createAccount']);
Route::get('/signup-bdd', [CreaCompteController::class, 'createAccount']);

