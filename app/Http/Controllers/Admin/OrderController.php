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
    
    public function detail($id)
    {
        return $this->successful(['list' => $this->orderServer->aOrderList(['order_id' => $id, 'first' => 1])]);
    }
    
    public function orderGoodsList()
    {
        return $this->successful(['list' => $this->orderServer->aOrderGoodsList($this->request->all())]);
    }
    
    public function payList()
    {
        $orderServer = new Pay;
        return $this->successful(['list' => $orderServer->aList($this->request->all())]);
    }
}
