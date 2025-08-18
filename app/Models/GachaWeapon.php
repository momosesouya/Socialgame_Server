<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class GachaWeapon extends Model
{
    use HasFactory;

    protected $table = 'gacha_weapons';
    protected $primaryKey = 'gacha_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        'created',
    ];

    public static function GetGachaWeapon()
    {
        $masterDataList = MasterDataService::GetMasterData('gacha_weapon');
        return $masterDataList;
    }
}
