<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SalesReturn extends Model
{
    public $timestamps = false;
    protected $table = 't_return';

    protected $fillable = [];

    /**
     * Get the comments for the blog post.
     */
    public function returnDetails()
    {
        return $this->hasMany('App\Model\ReturnDetail');
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
                
                static::addGlobalScope('shop_id', function (Builder $builder) use ($shop_arrays) {
                    $builder->whereIn('t_return.shop_id', $shop_arrays);
                });
            }

            if (Auth::check() and Auth::user()->role == config('constants.SHOP_ADMIN')) {
                static::addGlobalScope('shop_id', function (Builder $builder) {
                    $builder->where('t_return.shop_id', '=', Auth::user()->shop_id);
                });
            }
        }
    }
}
