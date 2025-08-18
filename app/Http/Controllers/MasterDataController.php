<?php

namespace App\Http\Controllers;
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
        $paymentShop = PaymentShop::GetPaymentShop();
        $weaponMaster = Weapon::GetWeaponMaster();
        $weaponCategory = WeaponCategory::GetWeaponCategory();
        $weaponRarity = WeaponRarity::GetWeaponRarity();
        $weaponExp = WeaponExp::GetWeaponExp();
        $gachaWeapon = GachaWeapon::GetGachaWeapon();
        $gachaPeriod = GachaPeriod::GetWeaponPeriodMaster();
        $itemMaster = Item::GetItem();
        $itemCategory = ItemCategory::GetItemCategory();

        $response = [
            'master_data_version' => config('constants.MASTER_DATA_VERSION'),
            'payment_shop' => $paymentShop,
            'weapon_master' => $weaponMaster,
            'weapon_category' => $weaponCategory,
            'weapon_rarity' => $weaponRarity,
            'weapon_exp' => $weaponExp,
            'gacha_weapon' => $gachaWeapon,
            'gacha_period' => $gachaPeriod,
            'item_master' => $itemMaster,
            'item_category' => $itemCategory,
        ];
        return response()->json($response);
    }
}
