<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Libs\MasterDataService;

class ItemCategory extends Model
{
    use HasFactory;

    protected $table = 'item_categories';
    protected $primaryKey = 'item_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

   // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetItemCategory()
    {
        $master_data_list = MasterDataService::GetMasterData('item_category');
        return $master_data_list;
    }
}
