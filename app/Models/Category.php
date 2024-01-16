<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    public $timestamps = false;
    protected $table = 'm_category';

    protected $fillable = [
        'id',
        'company_id',
        'parent_category_id',
        'name',
        'description',
        'is_deleted',
        'create_user_id',
        'update_user_id',
        'create_datetime',
        'update_datetime',
    ];

    public function shop()
    {
        return $this->belongsToMany('App\Models\Shop', 'm_shop_category', 'category_id', 'shop_id')->withTimestamps();
    }

    protected static function booted()
    {
        if (Auth::check() and Auth::user()->role != config('constants.ADMIN')) {
            if (Auth::user()->role == config('constants.COMPANY_ADMIN')) {
                static::addGlobalScope(function (Builder $builder) {
                    $builder->where('m_category.company_id', '=', Auth::user()->company_id);
                });
            }
            if (Auth::user()->role == config('constants.SHOP_ADMIN')) {
                static::addGlobalScope(function (Builder $builder) {
                    $builder->join('m_shop_category', 'm_shop_category.category_id', '=', 'm_category.id')
                        ->where('m_shop_category.shop_id', '=', Auth::user()->shop_id)
                        ->select('m_category.*');
                });
            }
        }
    }
}
