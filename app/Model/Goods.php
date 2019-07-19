<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'm_goods';
    protected $primaryKey = 'goods_id';
    public $timestamps = false;
}
