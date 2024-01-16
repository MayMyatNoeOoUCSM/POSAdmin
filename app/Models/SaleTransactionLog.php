<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleTransactionLog extends Model
{
    public $timestamps = false;
    protected $table = 't_sale_transaction_log';

    protected $fillable = [];
}
