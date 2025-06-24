<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\PaymentShop;

class BuyCurrencyController extends Controller
{
    public function __invoke(Request $request)
    {
        // $userData = User::where('user_id', $request->uid)->first();
        // $paymentData = PaymentShop::where('product_id', $request->pid)->first();
        // $walletBase = UserWallet::where('manage_id', $manage_id);
        // dd($request->famo)
        $result = 0;
        $errcode = '';
        $response = [];

        // $authUserData = Auth::user();
        // if (!$authUserData) {
        //     return response()->json([
        //         'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
        //     ], 401);
        // }

        // ユーザー情報取得
        $userData = User::where('user_id', $request->uid)->first();
        // if (!$userData) {
        //     return response()->json([
        //         'errcode' => 'ERRCODE_USER_NOT_FOUND',
        //     ], 404);
        // }

        $manage_id = $userData->manage_id;

        // ログインユーザーと照合
        // if ($manage_id != $authUserData->manage_id) {
        //     return response()->json([
        //         'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
        //     ], 403); // 403 Forbidden
        // }

        // ショップ情報取得
        $paymentData = PaymentShop::where('product_id', $request->pid)->first();
        $walletsData = UserWallet::where('manage_id', $manage_id)->first();

        // 指定された商品分通貨を増やす処理
        DB::transaction(function () use (&$result, $paymentData, &$walletsData) {
            \Log::debug('トランザクション開始');

            $walletsData->free_amount += $paymentData->bonus_currency;
            $walletsData->paid_amount += $paymentData->paid_currency;

            $walletsData->save();

            \Log::debug('ウォレット保存完了: free=' . $walletsData->free_amount . ', paid=' . $walletsData->paid_amount);

            $result = 1;
        });

        return response()->json([
            'wallets' => $walletsData->fresh(),
        ]);

        // if ($result == 0)
        // {
        //     return response()->json([
        //         'errcode' => config('constants.ERRCODE_CANT_BUY_CURRENCY'),
        //     ], 500);
        // }
        
    }
}