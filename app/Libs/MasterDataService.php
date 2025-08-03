<?php

namespace App\Libs;

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


class MasterDataService 
{
    public static function GenerateMasterData($version)
    {
        // 指定バージョンのファイルを作成
        touch(__DIR__ . '/' . $version);
        chmod(__DIR__ . '/' . $version, 0666);

        // master_dataを追加
        $master_data_list = [];
        $master_data_list['payment_shop'] = PaymentShop::all();
        $master_data_list['weapon_master'] = Weapon::all();
        $master_data_list['weapon_category'] = WeaponCategory::all();
        $master_data_list['weapon_exp'] = WeaponExp::all();
        $master_data_list['weapon_rarity'] = WeaponRarity::all();
        $master_data_list['gacha_weapon'] = GachaWeapon::all();
        $master_data_list['gacha_period'] = GachaPeriod::all();
        $master_data_list['item_master'] = Item::all();
        $master_data_list['item_category'] = ItemCategory::all();

        // JSONファイルを作成
        $json = json_encode($master_data_list);
        file_put_contents(__DIR__ . '/' . $version,$json);
    }

    /**
     * マスタデータ取得処理
     * 
     * @param data_name 取得データ名
     */
    public static function GetMasterData($data_name)
    {
        // ファイル取得
        $file = fopen(__DIR__ . '/' . config('constants.MASTER_DATA_VERSION'), "r");
        if(!$file){
            return false;
        }

        // データ取得
        $json = [];
        while ($line = fgets($file)){
            $json = json_decode($line, true);
        }
        if(!array_key_exists($data_name, $json)) {
            return false;
        }

        return $json[$data_name];
    }

    /**
     * マスタバージョンチェック処理
     * 
     * @param client_master_version マスタバージョン(クライアント)
     */
    public static function CheckMasterDataVersion($client_master_version)
    {
        return config('constants.MASTER_DATA_VERSION') <= $client_master_version;
    }
}