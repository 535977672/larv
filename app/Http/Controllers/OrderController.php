<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Service\Order as OrderService;

class OrderController extends Controller
{
    public function addOrder(Request $request)
    {
        $goodsId = FI($request->post('goodsId', 0));
        $attrId = FI($request->post('attrId', 0));
        $type = FI($request->post('type', 0));
        
        $order = new OrderService();
        $order->createOrder($request->all());
        return return_ajax(200, 'success');
    }
}
