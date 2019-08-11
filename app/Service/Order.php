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
    
    public function aOrderList($data, $limit = 20, $field = '*'){
        $where = [
            ['deleted', '=', 0],
        ];
        isset($data['paytype']) && $data['paytype'] && $where[] = ['paytype', '=', $data['paytype']];
        isset($data['order_status']) && $data['order_status'] >=0 && $where[] = ['order_status', '=', $data['order_status']];
        isset($data['pay_status']) && $data['pay_status'] >=0 && $where[] = ['pay_status', '=', $data['pay_status']];
        isset($data['type']) && $data['type'] && $where[] = ['type', '=', $data['type']];
        isset($data['order_sn']) && $data['order_sn'] && $where[] = ['order_sn', 'like', '%'.$data['order_sn'].'%'];
        isset($data['order_id']) && $data['order_id']>=0 && $where[] = ['order_id', '=', $data['order_id']];
        $oData = OrderModel::with(['ordergoods'])
            ->where($where)
            ->orderBy('order_id', 'desc')
            ->select(DB::raw($field));
        
        if(!(isset($data['first']) && $data['first'])) $list = $oData->paginate($limit);
        else $list = $oData->first();
        return $list;
    }
    
    public function aOrderGoodsList($data, $limit = 20, $field = '*'){
        if($field = '*') $field = 'og.*,o.pay_status,o.order_status,o.consignee,o.province,o.city,o.district,o.address,o.mobile,o.total_amount,o.order_amount,o.paytype';
        $where = [
            ['o.deleted', '=', 0],
        ];
        isset($data['is_buy']) && $data['is_buy'] >=0 && $where[] = ['og.is_buy', '=', $data['is_buy']];
        isset($data['is_send']) && $data['is_send'] >=0 && $where[] = ['og.is_send', '=', $data['is_send']];
        isset($data['goods_type']) && $data['goods_type'] >=0 && $where[] = ['og.goods_type', '=', $data['goods_type']];
        isset($data['start']) && $data['start'] && $where[] = ['o.add_time', '>=', strtotime($data['start'] . ' 00:00:00')];
        isset($data['end']) && $data['end'] && $where[] = ['o.add_time', '<=', strtotime($data['end'] . ' 23:59:59')];
        isset($data['pay_status']) && $data['pay_status'] >=0 && $where[] = ['o.pay_status', '=', $data['pay_status']];
        isset($data['order_sn']) && $data['order_sn'] && $where[] = ['og.goods_sn', 'like', '%'.$data['order_sn'].'%'];
        isset($data['paytype']) && $data['paytype'] && $where[] = ['o.paytype', '=', $data['paytype']];
        isset($data['order_status']) && $data['order_status'] >=0 && $where[] = ['o.order_status', '=', $data['order_status']];
        $list = OrderGoods::from('order_goods as og')
            ->where($where)
            ->join('order as o', 'o.order_id', '=', 'og.order_id')
            ->orderBy('og.og_id', 'desc')
            ->select(DB::raw($field))
            ->paginate($limit);
        return $list;
    }
}
