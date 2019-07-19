<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsExt extends Model
{
    protected $table = 'm_goods_ext';
    protected $primaryKey = 'goods_id';
    public $timestamps = false;
}
