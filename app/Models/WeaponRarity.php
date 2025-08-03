<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class WeaponRarity extends Model
{
    use HasFactory;

    protected $table = 'weapon_rarities';
    protected $primaryKey = 'rarity_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetWeaponRarity()
    {
        $master_data_list = MasterDataService::GetMasterData('weapon_rarity');
        return $master_data_list;
    }
}
