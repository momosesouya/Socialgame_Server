<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class Weapon extends Model
{
    use HasFactory;

    protected $table = 'weapons';
    protected $primaryKey = 'weapon_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetWeaponMaster() 
    {
        $masterDataList = MasterDataService::GetMasterData('weapon_master');
        return $masterDataList;
    }
}
