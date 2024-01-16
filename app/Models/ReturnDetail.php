<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    public $timestamps = false;
    protected $table = 't_return_details';

    protected $fillable = [];

    /**
     * Get the post that owns the comment.
     */
    public function saleReturn()
    {
        return $this->belongsTo('App\Models\SalesReturn');
    }
}
