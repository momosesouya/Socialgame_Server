<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BuyCurrencyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // $userData = User::where('user_id', $request->uid)->first();
        // $paymentData = PaymentShop::where('product_id', $request->pid)->first();
        // $walletBase = UserWallet::where('manage_id', $manage_id);
        // dd($request->famo)
        $result = 0;
        $errcode = '';
        $response = 0;

        // ログイン確認
        if (!Auth::hasUser()) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
            ];
            return json_encode($response);
        }

        $authUserData = Auth::user();
        // ユーザー情報取得
        $userData = User::where('user_id', $request->uid)->first();
        $manage_id = $userData->manage_id;

        // ログインしているユーザーが自分と違ったらリダイレクト
        if ($manage_id != $authUserData->manage_id) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
            ];
            return json_encode($response);
        }

        // ショップ情報取得
        $paymentData = PaymentShop::where('product_id', $request->pid)->first();
        $walletBase = UserWallet::where('manage_id', $manage_id);

        // 指定された商品分通貨を増やす処理
        DB::transaction(function () use (&$result, $manage_id, $paymentData, $walletBase) {
            $walletsData = $walletBase->first();
            $bonus_currency = $paymentData->bonus_currency;
            $paid_currency = $paymentData->paid_currency;
            $result = $walletBase->update([
                'free_amount' => $walletsData->free_amount + $bonus_currency,
                'paid_amount' => $walletsData->paid_amount + $paid_currency,
            ]);

            $result = 1;
        }); 

        switch($result)
        {
            case 0:
                $errcode = confin('constants.ERRCODE_CANT_BUY_CURRENCY');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response = [
                    'wallets' => UserWallet::where('manage_id',$userData->manage_id)->first(),
                 ];
                break;
        }
        return json_encode($response);
    }
}
