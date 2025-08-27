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
        $masterDataList = [];
        $masterDataList['payment_shop'] = PaymentShop::all();
        $masterDataList['weapon_master'] = Weapon::all();
        $masterDataList['weapon_category'] = WeaponCategory::all();
        $masterDataList['weapon_exp'] = WeaponExp::all();
        $masterDataList['weapon_rarity'] = WeaponRarity::all();
        $masterDataList['gacha_weapon'] = GachaWeapon::all();
        $masterDataList['gacha_period'] = GachaPeriod::all();
        $masterDataList['item_master'] = Item::all();
        $masterDataList['item_category'] = ItemCategory::all();

        // JSONファイルを作成
        $json = json_encode($masterDataList);
        file_put_contents(__DIR__ . '/' . $version,$json);
    }

    /**
     * マスタデータ取得処理
     * 
     * @param data_name 取得データ名
     */
    public static function GetMasterData($dataName)
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
        if(!array_key_exists($dataName, $json)) {
            return false;
        }

        return $json[$dataName];
    }

    /**
     * マスタバージョンチェック処理
     * 
     * @param client_master_version クライアントのマスタバージョン
     */
    public static function CheckMasterDataVersion($clientMasterVersion)
    {
        $serverVersion = config('constants.MASTER_DATA_VERSION');

        // クライアントの方が古いとき
        if ($clientMasterVersion < $serverVersion) {
            return false;
        }
        return true;
    }
}