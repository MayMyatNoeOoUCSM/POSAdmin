<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public $timestamps = false;
    protected $table = 't_stock';

    protected $fillable = [
        'id',
        'warehouse_id',
        'shop_id',
        'product_id',
        'quantity',
        'inout_flg',
        'source_location_id',
        'price',
        'remark',
        'is_deleted',
        'create_user_id',
        'update_user_id',
        'create_datetime',
        'update_datetime',
    ];
}
