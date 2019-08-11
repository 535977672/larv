<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PayRecord extends Model
{
    protected $table = 'pay_record';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function getTypeStrAttribute()
    {
        $value = $this->type;
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
    
    public function getStatusStrAttribute()
    {
        $value = $this->status;
        $str = '';
        switch($value){
            case 0:
                $str = '未支付';
                break;
            case 1:
                $str = '手动验证过期';
                break;  
            case 2:
                $str = '自动验证过期';
                break; 
            case 3:
                $str = '回调支付成功';
                break; 
            case 4:
                $str = '客户验证支付成功';
                break; 
            case 5:
                $str = '手动验证支付成功';
                break;
            case 6:
                $str = '数据重复';
                break;
            default:
                $str = '未知状态';
        }
        return $str;
    }
    
    public function getCreateTimeStrAttribute()
    {
        return date('Y-m-d H:i:s', $this->create_time);
    }
    
    public function getExpiringStrAttribute()
    {
        return date('Y-m-d H:i:s', $this->expiring);
    }
}
