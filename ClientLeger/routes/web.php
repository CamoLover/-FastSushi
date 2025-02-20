<?php

use App\Http\Controllers\CreaCompteController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/sign', function () {
    return view('sign');
});

Route::get('/testfooter', function () {
    return view('/module/footer');
});

Route::post('signup-bdd',[CreaCompteController::class, 'createAccount']);
Route::get('/signup-bdd', [CreaCompteController::class, 'createAccount']);

