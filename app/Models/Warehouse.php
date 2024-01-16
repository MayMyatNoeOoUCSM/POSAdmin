<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Warehouse extends Model
{
    public $timestamps = false;
    protected $table = 'm_warehouse';

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

    protected static function booted()
    {
        if (Auth::user()->role == config('constants.COMPANY_ADMIN')) {
            static::addGlobalScope('company_id', function (Builder $builder) {
                $builder->where('company_id', '=', Auth::user()->company_id);
            });
        }
        if (Auth::user()->role == config('constants.SHOP_ADMIN')) {
            static::addGlobalScope('id', function (Builder $builder) {
                $builder->where('id', '=', 0);
            });
        }
    }
}
