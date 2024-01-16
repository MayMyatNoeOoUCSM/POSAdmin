<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Terminal extends Model
{
    public $timestamps = false;
    protected $table = 'm_terminal';

    protected $fillable = [
        'id',
        'shop_id',
        'name',
        'is_deleted',
        'create_user_id',
        'update_user_id',
        'create_datetime',
        'update_datetime',
    ];

    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }


    protected static function booted()
    {
        if (Auth::check() and Auth::user()->role != config('constants.ADMIN')) {
            $shop        = \App\Models\Shop::all();
            $shop_arrays = $shop->reject(function ($shop) {
                return $shop->company_id !== Auth::user()->company_id;
            })->map(function ($shop) {
                return $shop->id;
            })->toArray();
          
            static::addGlobalScope('shop_id', function (Builder $builder) use ($shop_arrays) {
                $builder->whereIn('shop_id', $shop_arrays);
            });
        }

        if (Auth::user()->role != config('constants.COMPANY_ADMIN')
            and Auth::user()->role != config('constants.SHOP_ADMIN')) {
            static::addGlobalScope('shop_id', function (Builder $builder) {
                $builder->where('shop_id', Auth::user()->shop_id);
            });
        }
    }
}
