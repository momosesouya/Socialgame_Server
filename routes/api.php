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

// スタミナ消費
Route::post('/staminaConsumption', App\Http\Controllers\StaminaConsumptionController::class);

// ガチャ
Route::post('/gachaExecute', App\Http\Controllers\GachaExecuteController::class);

// ガチャログ
Route::post('/getGachaLog', App\Http\Controllers\GetGachaLogController::class);

// レベルアップ
Route::post('/levelUp', App\Http\Controllers\LevelUpController::class);


// マスタデータチェック
Route::post('/masterCheck', App\Http\Controllers\MasterDataCheckController::class);

// マスタデータ取得
Route::post('/masterGet', App\Http\Controllers\MasterDataController::class);

//マスターデータ挿入
Route::get('/addMasterData', App\Http\Controllers\AddMasterDataController::class);


// ガチャテスト用
Route::get('/gachaTest', App\Http\Controllers\Gacha\GachaTestController::class);
