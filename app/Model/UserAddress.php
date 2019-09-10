<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_address';
    protected $primaryKey = 'address_id';
    public $timestamps = false;
    
    protected $fillable = ['u_id', 'consignee', 'province', 'city', 'district', 'address', 'mobile'];
}
