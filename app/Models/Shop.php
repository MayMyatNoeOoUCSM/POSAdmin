<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{
    public $timestamps = false;
    protected $table = 'm_shop';
    protected $fillable = [
        'id',
        'name',
        'address',
        'phone_number_1',
        'phone_number_2',
        'is_deleted',
        'create_user_id',
        'update_user_id',
        'create_datetime',
        'update_datetime',
    ];

    public function product()
    {
        return $this->belongsToMany('App\Models\Product', 'm_shop_product', 'shop_id', 'product_id')->withPivot('shop');
    }
    
    protected static function booted()
    {
        if (Auth::check() and Auth::user()->role != config('constants.ADMIN')) {
            if (Auth::user()->role == config('constants.COMPANY_ADMIN')) {
                static::addGlobalScope('company_id', function (Builder $builder) {
                    $builder->where('m_shop.company_id', '=', Auth::user()->company_id);
                });
            }
            if (Auth::user()->role == config('constants.SHOP_ADMIN')) {
                static::addGlobalScope(function (Builder $builder) {
                    $builder->where('m_shop.id', '=', Auth::user()->shop_id);
                });
            }
        }
    }
}
