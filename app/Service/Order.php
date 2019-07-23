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

    /**
     * 
     * @param type $data
     * @param type $goodsData
     * @param type $payData
     * @return boolean
     * @throws Exception
     */
    public function createOrder($data, $goodsData, $payData){
        try {
            DB::beginTransaction();
            $id = OrderModel::insertGetId($data);
            if(!$id){
                throw new Exception('创建订单失败，请重试');
            }
            $goodsData['order_id'] = $id;
            if(!OrderGoods::insertGetId($goodsData)){
                throw new Exception('创建订单商品失败，请重试');
            }
            $payData['o_id'] = $id;
            if(!PayRecord::insertGetId($payData)){
                throw new Exception('创建支付失败，请重试');
            }
            DB::commit();
            return $id;
        } catch (Exception $exc) {
            DB::rollBack();
            $this->setErrorMsg($exc->getMessage());
            return false;
        }
    }
    
    public function getOrderDetail($id){
        OrderModel::find($id);    
    }
    
    public function getOrderList($uid, $limit = 20){
        OrderModel::where([
                ['u_id', '=', $uid],
                ['deleted', '=', 0],
            ])
            ->select(DB::raw('goods_id,goods_name,shop_price,original_img'))
            ->simplePaginate($limit);
    }
}
