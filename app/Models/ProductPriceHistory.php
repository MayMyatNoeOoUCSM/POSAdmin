<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
    public $timestamps = false;
    protected $table = 't_product_price_history';

    protected $fillable = [
        'id',
        'product_id',
        'price',
        'is_deleted',
        'create_user_id',
        'update_user_id',
        'create_datetime',
        'update_datetime',
    ];
}
