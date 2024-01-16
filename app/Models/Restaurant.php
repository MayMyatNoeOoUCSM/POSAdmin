<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Restaurant extends Model
{
    public $timestamps = false;
    protected $table = 'm_restaurant_table';

    protected $fillable = [
        'id',
        'shop_id',
        'name',
        'total_seats_people',
        'available_status',
        'create_user_id',
        'update_user_id',
        'create_datetime',
        'update_datetime',
    ];

    public function order()
    {
        return $this->hasMany('App\Models\Order', 'restaurant_table_id');
    }

    public function orderdetails()
    {
        return $this->hasManyThrough('App\Models\OrderDetails', 'App\Models\Order', 'restaurant_table_id', 'order_id')->where('order_status', '!=', config('constants.ORDER_INVOICE'));
    }

    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }

    protected static function booted()
    {
        if (Auth::user()->role == config('constants.CASHIER_STAFF')
            or Auth::user()->role == config('constants.KITCHEN_STAFF')
            or Auth::user()->role == config('constants.WAITER_STAFF')
            or Auth::user()->role == config('constants.SHOP_ADMIN')) {
            static::addGlobalScope('m_restaurant_table.shop_id', function (Builder $builder) {
                $builder->where('m_restaurant_table.shop_id', Auth::user()->shop_id);
            });
        }

        if (Auth::check() and Auth::user()->role == config('constants.COMPANY_ADMIN')) {
            $shop        = \App\Models\Shop::all();
            $shop_arrays = $shop->reject(function ($shop) {
                return $shop->company_id !== Auth::user()->company_id;
            })->map(function ($shop) {
                return $shop->id;
            })->toArray();
                
            static::addGlobalScope('m_restaurant_table.shop_id', function (Builder $builder) use ($shop_arrays) {
                $builder->whereIn('m_restaurant_table.shop_id', $shop_arrays);
            });
        }
    }
}
