<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    public $timestamps = false;
    protected $table = 't_sale_details';

    protected $fillable = [];

    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id')->select(['m_product.id','m_product.name']);
    }
}
