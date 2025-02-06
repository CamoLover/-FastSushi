<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CreaCompteController;
use App\Http\Controllers\SigninController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/reset-password', [PasswordResetController::class, 'sendResetLink']);

Route::post('/create-accompte', [CreaCompteController::class, 'createAccount']);

Route::post('/signin-bdd', [SigninController::class, 'signin']);