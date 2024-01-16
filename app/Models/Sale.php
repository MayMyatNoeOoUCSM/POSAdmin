<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sale extends Model
{
    public $timestamps = false;
    protected $table = 't_sale';

    protected $fillable = [];

    public function details()
    {
        return $this->hasMany('App\Models\SaleDetail');
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
                
                // $terminal_arrays = \App\Models\Terminal::select('id')
                //     ->whereIn('shop_id', $shop_arrays)
                //     ->get()
                //     ->toArray(); //dd($terminal_arrays);
                static::addGlobalScope('t_sale.shop_id', function (Builder $builder) use ($shop_arrays) {
                    $builder->whereIn('t_sale.shop_id', $shop_arrays);
                });
            }
            if (Auth::check() and Auth::user()->role == config('constants.SHOP_ADMIN')) {
                $terminal_arrays = \App\Models\Terminal::select('id')
                    ->where('shop_id', '=', Auth::user()->shop_id)
                    ->withoutGlobalScope('shop_id')
                    ->get()
                    ->toArray();
                
                static::addGlobalScope('terminal_id', function (Builder $builder) use ($terminal_arrays) {
                    $builder->whereIn('terminal_id', $terminal_arrays);
                });
            }
            if (Auth::check() and Auth::user()->role == config('constants.SALE_STAFF')) {
                static::addGlobalScope('shop_id', function (Builder $builder) {
                    $builder->where('shop_id', Auth::user()->shop_id);
                });
            }
        }
    }
}
