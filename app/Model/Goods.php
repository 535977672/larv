<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'm_goods';
    protected $primaryKey = 'goods_id';
    public $timestamps = false;
    
    
    public function attr()
    {
        return $this->hasMany('App\Model\GoodsAttr', 'goods_id', 'goods_id');
    }
    
    public function color()
    {
        return $this->hasMany('App\Model\GoodsColor', 'goods_id', 'goods_id');
    }
    
    public function ext()
    {
        return $this->hasOne('App\Model\GoodsExt', 'goods_id', 'goods_id');
    }
    
    
    
    public function getIsOnSaleStrAttribute()
    {
        $value = $this->is_on_sale;
        $str = '';
        switch($value){
            case 0:
                $str = '下架';
                break;
            case 1:
                $str = '上架';
                break;
            default:
                $str = '未知状态';
        }
        return $str;
    }
}
