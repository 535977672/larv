<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PayCode extends Model
{
    protected $table = 'pay_code';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'code', 'phone', 'create_time'];
}
