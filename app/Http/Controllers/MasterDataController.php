<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

// ショップ
use App\Models\PaymentShop;
// 武器
use App\Models\Weapon;
use App\Models\WeaponCategory;
use App\Models\WeaponExp;
use App\Models\WeaponRarity;
// ガチャ
use App\Models\GachaWeapon;
use App\Models\GachaPeriod;
// アイテム
use App\Models\Item;
use App\Models\ItemCategory;

class MasterDataController extends Controller
{
    public function __invoke()
    {
        // クライアント側に送信したいマスタデータだけ選択
        $payment_shop = PaymentShop::GetPaymentShop();
        $weapon_master = Weapon::GetWeaponMaster();
        $weapon_category = WeaponCategory::GetWeaponCategory();
        $weapon_rarity = WeaponRarity::GetWeaponRarity();
        $weapon_exp = WeaponExp::GetWeaponExp();
        $gacha_weapon = GachaWeapon::GetGachaWeapon();
        $gacha_period = GachaPeriod::GetWeaponPeriodMaster();
        $item_master = Item::GetItem();
        $item_category = ItemCategory::GetItemCategory();
        \Log::debug('取得した payment_shop:', ['data' => $payment_shop]);

        $responce = [
            'master_data_version' => config('constants.MASTER_DATA_VERSION'),
            'payment_shop' => $payment_shop,
            'weapon_master' => $weapon_master,
            'weapon_category' => $weapon_category,
            'weapon_rarity' => $weapon_rarity,
            'weapon_exp' => $weapon_exp,
            'gacha_weapon' => $gacha_weapon,
            'gacha_period' => $gacha_period,
            'item_master' => $item_master,
            'item_category' => $item_category,
        ];
        return response()->json($responce);
        // 現状すべてのデータを取得しているので指定されたものだけ取得できるようにしたい
    }
}
