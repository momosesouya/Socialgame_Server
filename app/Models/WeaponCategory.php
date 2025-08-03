<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class WeaponCategory extends Model
{
    use HasFactory;

    protected $table = 'weapon_categories';
    protected $primaryKey = 'weapon_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetWeaponCategory()
    {
        $master_data_list = MasterDataService::GetMasterData('weapon_category');
        return $master_data_list;
    }
}
