<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\PaymentShop;

class BuyCurrencyController extends Controller
{
    public function __invoke(Request $request)
    {
        // ユーザー情報取得
        $userData = User::where('user_id', $request->uid)->first();
        $manageId = $userData->manage_id;

        // ショップ情報取得
        $paymentData = PaymentShop::where('product_id', $request->pid)->first();
        $walletsData = UserWallet::where('manage_id', $manageId)->first();

        // 指定された商品分通貨を増やす処理
        DB::transaction(function () use ($paymentData, &$walletsData) {
            $walletsData->free_amount += $paymentData->bonus_currency;
            $walletsData->paid_amount += $paymentData->paid_currency;

            $walletsData->save();
        });

        return response()->json([
            'wallets' => $walletsData->fresh(),
        ]);       
    }
}