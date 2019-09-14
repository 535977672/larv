<?php

namespace App\Http\Controllers\Admin;

use App\Model\Order;
use App\Service\Order as OrderServer;
use App\Service\Pay;

class OrderController extends AdminController
{
    protected function initialize() 
    { 
        parent::initialize();
        $this->orderServer = new OrderServer;
    }
    
    /**
     * 订单列表
     */
    public function orderList(){
        return $this->successful(['list' => $this->orderServer->aOrderList($this->request->all())]);
    }
    
    /**
     * 删除数据检查
     * @return type
     */
    public function orderDel()
    {
        $ids = explode(',', $this->request->post('ids', ''));
        Order::whereIn('order_id', $ids)
          ->update(['deleted' => 1]);
        return $this->successful('保存成功');
    }
    
    /**
     * 订单详细
     * @param type $id
     * @return type
     */
    public function detail($id)
    {
        return $this->successful(['list' => $this->orderServer->aOrderList(['order_id' => $id, 'first' => 1])]);
    }
    
    /**
     * 订单商品列表
     * @return type
     */
    public function orderGoodsList()
    {
        return $this->successful(['list' => $this->orderServer->aOrderGoodsList($this->request->all())]);
    }
    
    /**
     * 订单商品列表发货
     * @return type
     */
    public function orderGoodsSend($id)
    {
        if(!$this->orderServer->orderGoodsSend($id)) return $this->failed($this->orderServer->getErrorMsg());
        return $this->successful();
    }
    
    /**
     * 订单商品列表发货物流
     * @return type
     */
    public function orderGoodsShip($id)
    {
        $code = $this->request->post('shipping_code', '');
        $name = $this->request->post('shipping_name', '');
        if(!$code) return $this->failed('添加物流单号');
        if(!$this->orderServer->orderGoodsShip($id, $code, $name)) return $this->failed($this->orderServer->getErrorMsg());
        return $this->successful();
    }
    
    /**
     * 支付列表
     * @return type
     */
    public function payList()
    {
        $orderServer = new Pay;
        return $this->successful(['list' => $orderServer->aList($this->request->all())]);
    }
    
    /**
     * 手动验证支付成功
     * @return $id
     */
    public function paySuccess($id)
    {
        $orderServer = new Pay;
        if(!$orderServer->paySuccess($id)) return $this->failed($orderServer->getErrorMsg());
        return $this->successful();
    }
    
    /**
     * 手动验证支付过期
     * @return $id
     */
    public function payOutExp($id)
    {
        $orderServer = new Pay;
        if(!$orderServer->payPersonExpireCheck($id)) return $this->failed($orderServer->getErrorMsg());
        return $this->successful();
    }
    
    
}
