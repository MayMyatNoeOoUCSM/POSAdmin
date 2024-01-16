<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class Staff extends Authenticatable
{
    use HasApiTokens, Notifiable;
    public $timestamps = false;

    protected $table = 'm_staff';

    protected $fillable = [
        'id',
        'staff_number',
        'password',
        'role',
        'staff_type',
        'position',
        'bank_account_number',
        'graduated_univeristy',
        'name',
        'gender',
        'nrc_number',
        'dob',
        'marital_status',
        'race',
        'city',
        'address',
        'photo',
        'phone_number_1',
        'phone_number_2',
        'join_from',
        'join_to',
        'staff_status',
        'create_user_id',
        'update_user_id',
        'create_datetime',
        'update_datetime',
    ];

    // private $authuser;
    // public function __construct(Request $request)
    // {
    //     $this->authuser = $request->user();
    // }
    //$user = Request::user();
    //
   
    protected static function booted()
    {
        if (Auth::guard('staff')->check() and Auth::guard('staff')->user()->role != config('constants.ADMIN')) {
            if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
                static::addGlobalScope('company_id', function (Builder $builder) {
                    $builder->where('m_staff.company_id', Auth::guard('staff')->user()->company_id);
                });
            }
            if (Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN')) {
                static::addGlobalScope('shop_id', function (Builder $builder) {
                    $builder->where('m_staff.shop_id', Auth::guard('staff')->user()->shop_id);
                });
            }
        }
    }
}
