<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'order_id';
    public $timestamps = false;
    
    public function ordergoods()
    {
        return $this->hasMany('App\Model\OrderGoods', 'order_id', 'order_id');
    }
    
    public function getOrderStatusStrAttribute()
    {
        $value = $this->order_status;
        $str = '';
        switch($value){
            case 0:
                $str = '待确认';
                break;
            case 1:
                $str = '已确认';
                break;  
            case 2:
                $str = '已收货';
                break; 
            case 3:
                $str = '已取消';
                break; 
            case 4:
                $str = '已完成';
                break; 
            case 5:
                $str = '已作废';
                break;
            default:
                $str = '未知状态';
        }
        return $str;
    }
    
    public function getPayStatusStrAttribute()
    {
        $value = $this->pay_status;
        $str = '';
        switch($value){
            case 0:
                $str = '未支付';
                break;
            case 1:
                $str = '已支付';
                break;  
            case 3:
                $str = '已退款';
                break; 
            case 4:
                $str = '已完成';
                break; 
            case 5:
            default:
                $str = '拒绝退款';
        }
        return $str;
    }
    
    public function getPaytypeStrAttribute()
    {
        $value = $this->paytype;
        $str = '';
        switch($value){
            case 1:
                $str = '支付宝';
                break;  
            case 2:
                $str = '微信';
                break;
            default:
                $str = '';
        }
        return $str;
    }
    
    public function getTypeStrAttribute()
    {
        $value = $this->type;
        $str = '';
        switch($value){
            case 1:
                $str = '普通商品';
                break;  
            case 2:
                $str = '套餐商品';
                break;
            default:
                $str = '';
        }
        return $str;
    }
}