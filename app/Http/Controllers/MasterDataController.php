<?php

namespace App\Http\Controllers;

// ショップ
use App\Models\PaymentShop;

class MasterDataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        // クライアント側に送信したいマスタデータだけ選択
        $payment_shop = PaymentShop::GetPaymentShop();

        $responce = [
            'payment_shop' => $payment_shop,
        ];
        return json_encode($responce);
    }
}
