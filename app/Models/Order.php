<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    public $timestamps = false;
    protected $table = 't_order';


    protected $guarded = [];
    
    public function details()
    {
        return $this->hasMany('App\Models\OrderDetails');
    }

    public function restauranttable()
    {
        return $this->belongsTo('App\Models\Restaurant', 'restaurant_table_id');
    }
    protected static function booted()
    {
        if (Auth::check() and Auth::user()->role != config('constants.ADMIN')) {
            if (Auth::check() and Auth::user()->role == config('constants.COMPANY_ADMIN')) {
                $shop        = \App\Models\Shop::all();
                $shop_arrays = $shop->reject(function ($shop) {
                    return $shop->company_id !== Auth::user()->company_id;
                })->map(function ($shop) {
                    return $shop->id;
                })->toArray();
                
                static::addGlobalScope('t_order.shop_id', function (Builder $builder) use ($shop_arrays) {
                    $builder->whereIn('t_order.shop_id', $shop_arrays);
                });
            }

            if (Auth::check() and Auth::user()->role == config('constants.SHOP_ADMIN')) {
                static::addGlobalScope('t_order.shop_id', function (Builder $builder) {
                    $builder->where('t_order.shop_id', Auth::user()->shop_id);
                });
            }

            if (Auth::user()->role == config('constants.CASHIER_STAFF')
                or Auth::user()->role == config('constants.KITCHEN_STAFF')
                or Auth::user()->role == config('constants.WAITER_STAFF')) {
                static::addGlobalScope('t_order.shop_id', function (Builder $builder) {
                    $builder->where('t_order.shop_id', Auth::user()->shop_id);
                });
            }
        }
    }
}
