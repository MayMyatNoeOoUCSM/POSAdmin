<?php

namespace App\Models;

use App\Scopes\ShopScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    public $timestamps = false;
    protected $table = 'm_product';
    // protected $primaryKey = 'id';
    protected $fillable = [
        "product_type_id","name","barcode","description","mfd_date","expire_date",
        "create_user_id","update_user_id","create_datetime","update_datetime"
    ];


    public function shop()
    {
        return $this->belongsToMany('App\Models\Shop', 'm_shop_product', 'product_id', 'shop_id')->withPivot('product_id', 'shop_id')->withTimestamps();
    }

    // public function shopScope()
    // {
    //     if (Auth::user()->role == config('constants.SHOP_ADMIN')) {
    //         return $this->shop()->wherePivot('shop_id', '=', Auth::user()->shop_id);
    //     }
    // }

    // public function scopeShopList($query)
    // {
    //  if (Auth::user()->role == config('constants.SHOP_ADMIN')) {
    //      return $query->join('m_shop_product','m_shop_product.product_id','=','m_product.id')
    //                   ->where('m_shop_product.shop_id','=',1);
    //  }
    //}
    //
    //  public function find($id) {
    //   return Product::select('m_product.*')->where('m_product.id','=',$id)->first();
    // }
//     public function getRouteKeyName()
//     {
//         return 'id';
//     }

    // public function category()
    // {
    //     return $this->belongsToMany('App\Models\Category','m_product_category', 'product_id', 'category_id');
    // }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'product_type_id');
    }

    public function product_shop()
    {
        return $this->hasMany('App\Models\ShopProduct', 'product_id');
    }

    public function warehouse_shop_product()
    {
        return $this->hasMany('App\Models\WarehouseShopProductRel', 'product_id', 'id');
    }
   
    protected static function booted()
    {
        parent::boot();

        static::addGlobalScope(new ShopScope());
    }
}
