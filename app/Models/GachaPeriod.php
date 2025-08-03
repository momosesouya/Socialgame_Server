<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class GachaPeriod extends Model
{
    use HasFactory;

    protected $table = 'gacha_periods';
    protected $primaryKey = 'gacha_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetWeaponPeriodMaster() 
    {
        $master_data_list = MasterDataService::GetMasterData('gacha_period');
        return $master_data_list;
    }
}
