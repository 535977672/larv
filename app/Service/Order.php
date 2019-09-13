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
     * 添加订单
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
            
            if(isset($goodsData[0]) && is_array($goodsData[0])){
                foreach($goodsData as $k=>$v){
                    $goodsData[$k]['order_id'] = $id;
                }
            }else{
                $goodsData['order_id'] = $id;
            }
            if(!OrderGoods::insert($goodsData)){
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
    
    /**
     * 订单详细
     * @param type $id
     * @return type
     */
    public function getOrderDetail($id){
        return OrderModel::with(['ordergoods'])
        ->where('order_id', $id)->first();
    }
    
    /**
     * 删除订单
     * @param type $id
     * @param type $uid
     * @return type
     */
    public function orderDel($id, $uid){
        return OrderModel::where('order_id', $id)->where('u_id', $uid)->update(['deleted'=>1]);
    }
    
    /**
     * 确认收货
     * @param type $id
     * @param type $uid
     * @return type
     */
    public function orderQuest($id, $uid){
        try {
            DB::beginTransaction();
            $goods = OrderGoods::find($id);
            $order = OrderModel::where('order_id', $goods->order_id)->where('u_id', $uid)->first();
            if(!$goods || !$order) throw new Exception('订单不存在');
            if($order->order_status != 1 || $order->pay_status != 1)  throw new Exception('订单状态错误');
            $goods->is_receive = 1;
            $goods->confirm_time = time();
            if(!$goods->save()){
                throw new Exception('确认收货失败');
            }
            $goods = OrderGoods::where('order_id', $goods->order_id)->where('is_receive', 0)->first();
            if(!$goods){
                $order->order_status = 2;
                $order->confirm_time = time();
                if(!$order->save()){
                    throw new Exception('确认收货失败');
                }
            }
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollBack();
            $this->setErrorMsg($exc->getMessage());
            return false;
        }
    }
    
    /**
     * 订单列表
     * @param type $uid
     * @param type $limit
     * @return type
     */
    public function getOrderList($uid, $limit = 20){
        return OrderModel::with(['ordergoods' => function ($query) {
                $query->select(DB::raw('order_id,goods_name,goods_num,spec_key,shipping_code,shipping_name,img'));//order_id必须
            }])
            ->where([
                ['u_id', '=', $uid],
                ['deleted', '=', 0],
            ])
            ->select(DB::raw('order_id,order_sn,u_id,order_amount,add_time,type,pay_status,order_status'))
            ->orderBy('order_id', 'desc')
            ->simplePaginate($limit);
    }
    
    
    /**admin**************************************************************************/
    /**
     * 
     * @param type $data
     * @param type $limit
     * @param type $field
     * @return type
     */
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
    
    /**
     * 
     * @param type $data
     * @param type $limit
     * @param type $field
     * @return type
     */
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
        isset($data['is_send']) && $data['is_send'] >=0 && $where[] = ['og.is_send', '=', $data['is_send']];
        $list = OrderGoods::from('order_goods as og')
            ->where($where)
            ->join('order as o', 'o.order_id', '=', 'og.order_id')
            ->orderBy('og.og_id', 'desc')
            ->select(DB::raw($field))
            ->paginate($limit);
        return $list;
    }
    
    /**
     * 订单商品列表发货
     * @param type $id
     * @param type $uid
     * @return type
     */
    public function orderGoodsSend($id){
        try {
            DB::beginTransaction();
            $goods = OrderGoods::find($id);
            $order = OrderModel::find($goods->order_id);
            if(!$goods || !$order) throw new Exception('订单不存在');
            if($order->order_status != 0 || $order->pay_status != 1)  throw new Exception('订单状态错误');
            $goods->is_send = 1;
            if(!$goods->save()){
                throw new Exception('确认发货失败');
            }
            $goods = OrderGoods::where('order_id', $goods->order_id)->where('is_send', 0)->first();
            if(!$goods){
                $order->order_status = 1;
                $order->shipping_time = time();
                if(!$order->save()){
                    throw new Exception('确认发货失败');
                }
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
