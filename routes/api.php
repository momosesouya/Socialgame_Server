<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', App\Http\Controllers\RegistrationController::class);

Route::post('/login', App\Http\Controllers\LoginController::class);