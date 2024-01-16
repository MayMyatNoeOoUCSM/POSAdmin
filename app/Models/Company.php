<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $timestamps = false;
    protected $table = 'm_company';

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
}
