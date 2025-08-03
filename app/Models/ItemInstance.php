<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemInstance extends Model
{
    use HasFactory;

    protected $table = 'item_instances';
    protected $primaryKey = ['manage_id', 'item_id'];

    // オートインクリメントを無効化
    public $incrementing = false;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $fillable = [
        'has_enhancement_item',
        'has_stamina_item',
        'has_exchange_item',
    ];

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];
}
