<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsColor extends Model
{
    protected $table = 'm_goods_color';
    protected $primaryKey = 'color_id';
    public $timestamps = false;
}
