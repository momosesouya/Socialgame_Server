<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 登録
Route::post('/register', App\Http\Controllers\RegistrationController::class);

// ログイン
Route::post('/login', App\Http\Controllers\LoginController::class);

// ホーム
Route::post('/home', App\Http\Controllers\HomeController::class);

// 通貨購入
Route::post('/buyCurrency', App\Http\Controllers\BuyCurrencyController::class);

// スタミナ回復
Route::post('/staminaRecovery', App\Http\Controllers\StaminaRecoveryController::class);

// // マスタデータチェック
Route::post('/masterCheck', App\Http\Controllers\MasterDataCheckController::class);

// マスタデータ取得
Route::post('/masterGet', App\Http\Controllers\MasterDataController::class);