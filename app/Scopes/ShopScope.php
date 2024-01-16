<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ShopScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check() and Auth::user()->role != config('constants.ADMIN')) {
            if (Auth::user()->role == config('constants.COMPANY_ADMIN')) {
                $builder->where('m_product.company_id', '=', Auth::user()->company_id);
            }
            if (Auth::user()->role == config('constants.SHOP_ADMIN') or
                Auth::user()->role == config('constants.WAITER_STAFF') or
                Auth::user()->role == config('constants.SALE_STAFF')
                ) {
                $builder->join('m_shop_product', 'm_shop_product.product_id', '=', 'm_product.id')
                    ->where('m_shop_product.shop_id', '=', Auth::user()->shop_id)
                    ->select('m_product.*');
            }
        }
    }
}
