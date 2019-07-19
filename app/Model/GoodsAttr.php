<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsAttr extends Model
{
    protected $table = 'm_goods_attr';
    protected $primaryKey = 'attr_id';
    public $timestamps = false;
}
