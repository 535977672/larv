<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PayRecord extends Model
{
    protected $table = 'pay_record';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
