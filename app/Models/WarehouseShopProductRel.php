<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WarehouseShopProductRel extends Model
{
    public $incrementing = false;
    public $primaryKey = null;
    public $timestamps = false;
    protected $table = 't_warehouse_shop_product';

    protected $fillable = [
        'warehouse_id',
        'shop_id',
        'product_id',
        'quantity',
        'minimum_quantity',
        'price',
    ];

    protected static function booted()
    {
        if (Auth::check() and Auth::user()->role != config('constants.ADMIN')) {
            if (Auth::user()->role == config('constants.COMPANY_ADMIN')) {
                $shop        = \App\Models\Shop::all();
                $shop_arrays = $shop->reject(function ($shop) {
                    return $shop->company_id !== Auth::user()->company_id;
                })->map(function ($shop) {
                    return $shop->id;
                })->toArray();
                $warehouse_arrays = \App\Models\Warehouse::select('m_warehouse.id')->where('company_id', '=', Auth::user()->company_id)->get()->toArray();
                static::addGlobalScope(function (Builder $builder) use ($shop_arrays, $warehouse_arrays) {
                    $builder->whereIn('t_warehouse_shop_product.shop_id', $shop_arrays)
                        ->orWhereIn('t_warehouse_shop_product.warehouse_id', $warehouse_arrays);
                });
            }
            if (Auth::user()->role == config('constants.SHOP_ADMIN')
                or
                Auth::user()->role == config('constants.WAITER_STAFF')
                or
                Auth::user()->role == config('constants.SALE_STAFF')) {
                static::addGlobalScope(function (Builder $builder) {
                    $builder->where('t_warehouse_shop_product.shop_id', Auth::user()->shop_id);
                });
            }
        }
    }
}
