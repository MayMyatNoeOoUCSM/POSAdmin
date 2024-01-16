<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Damage extends Model
{
    public $timestamps = false;
    protected $table = 't_damage_loss';

    protected $fillable = [
        'warehouse_id',
        'shop_id',
        'return_id',
        'damage_loss_date',
        'remark',
        'is_deleted',
        'create_user_id',
        'update_user_id',
        'create_datetime',
        'update_datetime',

    ];

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
                
                $warehouse_arrays = \App\Models\Warehouse::select('id')
                    ->where('company_id', Auth::user()->company_id)
                    ->get()
                    ->toArray();
                static::addGlobalScope(function (Builder $builder) use ($shop_arrays, $warehouse_arrays) {
                    $builder->whereIn('t_damage_loss.shop_id', $shop_arrays)
                        ->orWhereIn('t_damage_loss.warehouse_id', $warehouse_arrays);
                });
            }

            if (Auth::check() and Auth::user()->role == config('constants.SHOP_ADMIN')) {
                static::addGlobalScope('shop_id', function (Builder $builder) {
                    $builder->where('t_damage_loss.shop_id', '=', Auth::user()->shop_id);
                });
            }
        }
    }
}
