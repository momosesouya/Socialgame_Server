<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class WeaponExp extends Model
{
    use HasFactory;

    protected $table = 'weapon_exps';
    protected $primaryKey = 'rarity_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetWeaponExp()
    {
        $masterDataList = MasterDataService::GetMasterData('weapon_exp');
        return $masterDataList;
    }
}
