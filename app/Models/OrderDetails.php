<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderDetails extends Model
{
    public $timestamps = false;
    protected $table = 't_order_details';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id')->select(['m_product.id','m_product.name']);
    }

    // protected static function booted()
    // {
    //     // if (Auth::user()->role == config('constants.CASHIER_STAFF')
    //     //     or Auth::user()->role == config('constants.KITCHEN_STAFF')
    //     //     or Auth::user()->role == config('constants.WAITER_STAFF')) {
    //     //     static::addGlobalScope(function (Builder $builder) {
    //     //         $builder->join('t_order', 't_order.id', '=', 't_order_details.order_id')
    //     //        ->where('t_order.shop_id', Auth::user()->shop_id);
    //     //     });
    //     // }
    // }
}
