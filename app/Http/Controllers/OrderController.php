<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Order as OrderService;
use App\Service\Goods;
use App\Service\Pay;
use App\Service\File;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function __construct() {}

    /**
     * 创建order
     * @param Request $request
     * @return type
     */
    public function addOrder(Request $request)
    {
        $validator = $this->validateAddOrder($request->all());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return return_ajax(0, $errors[0]);
        }
        
        $param = [];
        $goodsId = $request->post('goodsId');
        $attrId = $request->post('attrId', 0);
        $param['type'] = $request->post('type');
        $param['city'] = $request->post('city');
        $param['district'] = $request->post('district');
        $param['province'] = $request->post('province');
        $param['address'] = $request->post('address');
        $param['consignee'] = $request->post('consignee');
        $num = $request->post('num');
        $user = $request->user();
        if ($user) {
            $param['u_id'] = $user->id;
            $param['mobile'] = $user->name;
        }else{
            $param['u_id'] = FI($request->post('u_id', 0));
            $param['mobile'] = FI($request->post('mobile', ''));
            if(!$param['mobile'] || !$param['u_id']){
                return return_ajax(0, '参数错误');
            }
        }
        
        $param['order_sn'] = date('YmdHis').getRandStr(6,3);
        $param['order_status'] = 0;
        $param['pay_status'] = 0;
        
        //获取商品
        $goodsModel = new Goods();
        $goods = $goodsModel->getGoods($goodsId);
        if(!$goods || FI($goods->is_on_sale) != 1){
            return return_ajax(0, '商品不存在');
        }
        if($goods->type == 1){
            $attr = $goodsModel->getGoodsAttr($attrId);
            if(!$attr || FI($attr->goods_id) != $goodsId){
                return return_ajax(0, '参数错误');
            }
            if(FI($attr->num) < $num){
                return return_ajax(0, '库存不足');
            }
            $price = FI($attr->attr_price);
        }else if($goods->type == 2){
            //套餐
            if(FI($goods->store_count) < $num){
                return return_ajax(0, '库存不足');
            }
            $price = FI($goods->shop_price);
        }else{
            return return_ajax(0, '参数错误');
        }
        
        $param['type'] = $goods['type'];
        $param['add_time'] = time();
        $exp = $param['add_time'] + 5*60;
        $param['goods_price'] = $price;//商品价格
        $param['total_amount'] = $price*$num;//订单总价
        $newPrice = $param['order_amount'] = $param['total_amount'];//支付=订单总价-积分-随机立减
        
        //redis控制价格唯一
        $i = 10;
        while($i > 0){
            if(Cache::store('redis')->tags(['payGoodsMoney'])->add($newPrice, '1', \Carbon\Carbon::parse(date('Y-m-d H:i:s', $exp)))){
                $param['order_amount'] = $newPrice;
                $i = -1;
            }else{
                $newPrice = $param['order_amount'] - $i;
                $i--;
            }
        }
        if($i != -1){
            return return_ajax(0, '用户过多，请稍后重试');
        }

        $pay = new Pay();
        $money = $pay->getMoney(1);
        if(in_array($newPrice, $money)){
            Cache::store('redis')->tags(['payGoodsMoney'])->forget($newPrice);
            return return_ajax(0, '用户过多，请稍后重试');
        }
        
        //先获取二维码
        $file = new File();
        $qrcode = $file->payFileCopy($newPrice, 1);
        if(!$qrcode){
            return return_ajax(0, '系统繁忙，请稍后重试');
        }
        
        $goodsParam = [
            'order_id' => 0,
            'goods_id' => $goods->goods_id,
            'goods_name' => $goods->goods_name,
            'goods_sn' => $param['order_sn'],
            'goods_num' => $num,
            'goods_price' => $param['goods_price'],
            'final_price' => $param['order_amount'],
            'arrt_id' => $attrId,
            'spec_key' => '',
            'spec_key_name' => '',
            'goods_type' => $param['type'],
        ];
        
        $payParam = [
            'o_id' => 0,
            'money' => $param['order_amount'],
            'type' => $param['type'],
            'create_time' => $param['add_time'],
            'expiring' => $exp,
            'phone' => 0,
            'ip' => get_real_ip(),
            'u_id' => 0,
        ];
        
        //价格已计算好
        $order = new OrderService();
        if($order->createOrder($param, $goodsParam, $payParam) === false){
            return return_ajax(0, $order->getErrorMsg());
        }
        return return_ajax(200, 'success');
    }
    
    /**
     * 验证order参数
     * @param type $data
     * @return type
     */
    protected function validateAddOrder($data)
    {
        return Validator::make($data, [
            'goodsId' => "bail|required|integer",
            'attrId' => "integer",
            'type' => "bail|required|integer",
            'city' => "bail|required|integer",
            'district' => "bail|required|integer",
            'province' => "bail|required|integer",
            'address' => "bail|required|string",
            'consignee' => "bail|required|string",
            'num' => "bail|required|string",
            'u_id' => "integer",
            'mobile' => "regex:'^[1][3,4,5,6,7,8,9][0-9]{9}$'",
        ], [
            'goodsId.required' => '参数错误',
            'goodsId.integer' => '参数错误',
            'attrId.integer' => '参数错误',
            'type.required' => '参数错误',
            'type.integer' => '参数错误',
            'city.required' => '地址错误',
            'city.integer' => '地址错误',
            'district.required' => '地址错误',
            'district.integer' => '地址错误',
            'province.required' => '地址错误',
            'province.integer' => '地址错误',
            'address.required' => '地址错误',
            'address.integer' => '地址错误',
            'consignee.required' => '收货人错误',
            'consignee.integer' => '收货人错误',
            'u_id.integer' => '参数错误',
            'mobile.regex' => '手机错误',
            'num.required' => '数量错误',
            'num.integer' => '数量错误',
        ]);
    }
}
