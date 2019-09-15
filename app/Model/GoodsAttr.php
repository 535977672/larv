<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsAttr extends Model
{
    protected $table = 'm_goods_attr';
    protected $primaryKey = 'attr_id';
    public $timestamps = false;
    
    public function goods()
    {
        return $this->hasOne('App\Model\Goods', 'goods_id', 'goods_id');
    }
    
    public function color()
    {
        return $this->hasOne('App\Model\GoodsColor', 'color_id', 'color_id');
    }
}
