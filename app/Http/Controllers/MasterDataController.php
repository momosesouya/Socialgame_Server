<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

// ショップ
use App\Models\PaymentShop;

class MasterDataController extends Controller
{
    public function __invoke()
    {
        // クライアント側に送信したいマスタデータだけ選択
        $payment_shop = PaymentShop::GetPaymentShop();
        \Log::debug('取得した payment_shop:', ['data' => $payment_shop]);

        $responce = [
            'master_data_version' => config('constants.MASTER_DATA_VERSION'),
            'payment_shop' => $payment_shop,
        ];
        return response()->json($responce);
        // 現状すべてのデータを取得しているので指定された商品だけ取得できるようにしたい
    }
}
