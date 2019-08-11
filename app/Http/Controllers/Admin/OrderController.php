<?php

namespace App\Http\Controllers\Admin;

use App\Model\Order;
use App\Service\Order as OrderServer;
use App\Service\Pay;

class OrderController extends AdminController
{
    public function orderList(){
        $orderServer = new OrderServer;
        return $this->successful(['list' => $orderServer->aOrderList($this->request->all())]);
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
        $orderServer = new OrderServer;
        return $this->successful(['list' => $orderServer->aOrderList(['order_id' => $id, 'first' => 1])]);
    }
    
    public function orderGoodsList()
    {
        $orderServer = new OrderServer;
        return $this->successful(['list' => $orderServer->aOrderGoodsList($this->request->all())]);
    }
    
    public function payList()
    {
        $orderServer = new Pay;
        return $this->successful(['list' => $orderServer->aList($this->request->all())]);
    }
}
