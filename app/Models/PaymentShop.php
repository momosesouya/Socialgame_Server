<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class PaymentShop extends Model
{
    use HasFactory;

    protected $table = 'payment_shops';
    protected $primaryKey = 'product_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 更新しないカラムのリスト
    protected $guarded = [
        'manage_id',
        'created',
    ];

    // マスタデータ取得
    public static function GetPaymentShop()
    {
        $payment_shop_data_list = MasterDataService::GetMasterData('payment_shop');
        return $payment_shop_data_list;
    }
}
