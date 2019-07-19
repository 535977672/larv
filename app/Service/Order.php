<?php
namespace App\Service;

use App\Model\Order as OrderModel;
use App\Model\OrderGoods;
use App\Model\PayRecord;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * 
 */
class Order extends Service{

    public function createOrder($data, $goodsData, $payData){
        try {
            DB::beginTransaction();
            $id = OrderModel::insertGetId($data);
            if(!$id){
                throw new Exception('创建订单失败，请重试');
            }
            if(!PayRecord::insertGetId($goodsData)){
                throw new Exception('创建支付失败，请重试');
            }
            if(!OrderGoods::insertGetId($payData)){
                throw new Exception('创建订单商品失败，请重试');
            }
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollBack();
            $this->setErrorMsg($exc->getMessage());
            return false;
        }
    }
    
}
