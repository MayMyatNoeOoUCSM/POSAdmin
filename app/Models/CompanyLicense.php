<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLicense extends Model
{
    public $timestamps = false;
    protected $table = 'm_company_license';

    protected $fillable = [
        'id',
        'company_id',
        'start_date',
        'end_date',
        'license_type',
        'status',
        'same_company_contact_flag',
        'contact_person',
        'contact_phone',
        'contact_email',
        'create_user_id',
        'update_user_id',
        'create_datetime',
        'update_datetime',
    ];
}
