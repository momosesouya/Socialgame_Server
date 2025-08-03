<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeaponInstance extends Model
{
    use HasFactory;

    protected $table = 'weapon_instances';
    protected $primaryKey = ['manage_id','weapon_id'];

    public $incrementing = false;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        'created',
    ];
}
