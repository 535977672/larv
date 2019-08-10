<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Service\Order as OrderService;
use App\Service\Goods;
use App\Service\Pay;
use App\Service\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Model\UserAddress;

class OrderController extends Controller
{
    
    /**
     * 确认订单
     * @param type $type
     * @param type $id
     * @param type $num
     * @param type $price
     */
    public function orderRequest($type, $id, $num, $price, $guestuid)
    {
        $time = time();
        $exp = $time + 5*60;
        $ip = $this->request->ip();
        $key = md5($ip . $guestuid);
        $randstr = getRandStr();
        if(!Cache::store('redis')->tags(['payGoodsMoneyPerIp'])->add($key, 1, \Carbon\Carbon::parse(date('Y-m-d H:i:s', $time+10)))){
            return $this->failed('操作频繁');
        }
        $param = compact("randstr", "time", "guestuid", "num");
        
        $goodsModel = new Goods();
        if($type==1){
            $attrId = $id;
            $attr = $goodsModel->getGoodsAttr($attrId);
            if(!$attr) return $this->failed('商品已下架');
            if(FI($attr->num) < $num) return $this->failed('库存不足');
            $oprice = FI($attr->attr_price);
            $goodsId = $attr->goods_id;
            $color = '';
            if($attr->color_id>0){
                $color = $goodsModel->getGoodsColor($attr->color_id);
            }
        }else if($type==2){
            $goodsId = $id;
        }else{
            return $this->failed('参数错误');
        }
        $goods = $goodsModel->getGoods($goodsId);
        if(!$goods || $goods->is_on_sale != 1) return $this->failed('商品已下架');
        $param['goods_name'] = $goods->goods_name;
        if($type==2){
            if(FI($goods->store_count) < $num) return $this->failed('库存不足');
            $oprice = FI($goods->shop_price);
            $subgoods = $goodsModel->getSubGoodsAttr($goods->ids);
            if(!$subgoods) return $this->failed('商品已下架');
            
            $attr = $colorname = $img = '';
            $param['type'] = 2;
            $param['img'] = $goods->original_img;
            $param['goods_id'] = $goodsId;
            $param['attr'] = '';
            $param['colorname'] = '';
            foreach ($subgoods as $v){
                $attrname = $colorname = $img = '';
                if($v->attrimg) $img = $v->attrimg;
                else if($v->colorimg) $img = $v->colorimg;
                else $img = $v->original_img;
                if($v->attr) $attrname = $v->attr;
                if($v->color) $colorname = $v->color;
                $param['sub'][] = [
                    'img' => $img,
                    'attr' => $attrname,
                    'colorname' => $colorname,
                    'goods_name' => $v->goods_name
                ];
            }
        }else{
            $attrname = $colorname = $img = '';
            if($attr->img) $img = $attr->img;
            else if($color && $color->img) $img = $color->img;
            else $img = $goods->original_img;
            if($attr->attr) $attrname = $attr->attr;
            if($color && $color->color) $colorname = $color->color;
            $param['type'] = 1;
            $param['attr_id'] = $id;
            $param['img'] = $img;
            $param['attr'] = $attrname;
            $param['colorname'] = $colorname;
            $param['color_id'] = $attr->color_id;
            $param['goods_id'] = $goodsId;
        }
        if($num*$oprice != $price){
            return $this->failed('非法操作');
        }
        $param['uaddr'] = ['consignee' => '', 'province' => '', 'city' => '', 'district' => '', 'address' => '', 'mobile' => ''];
        $user = $this->request->user();
        if ($user) {
            //收货地址
            $addr = UserAddress::where('user_id', $user->id)->first();
            if($addr) $param['uaddr'] = $addr->toArray();
        }
        
        $param['order_amount'] = $num*$oprice;//订单总价
        $newPrice = $param['order_amount'];//支付=订单总价-积分-随机立减
        
        //redis控制价格唯一
        $i = 15;
        $srand = 1;
        $erand = 49;
        $mark = 5;
        while($i > 0){
            if(Cache::store('redis')->tags(['payGoodsMoney'])->get($newPrice)){
                $newPrice = $param['order_amount'] - mt_rand($srand, $erand);
                $i--;
            }else if(Cache::store('redis')->tags(['payGoodsMoneyPer'])->add($newPrice, $key, \Carbon\Carbon::parse(date('Y-m-d H:i:s', $exp)))){
                $param['discount_money'] = $param['order_amount'] - $newPrice;
                $param['order_amount'] = $newPrice;
                $i = -1;
            }else{
                $newPrice = $param['order_amount'] - mt_rand($srand, $erand);
                $i--;
            }
            if($i < $mark && $srand < 50){
                $srand = 50;
                $erand = 99;
            }
        }
        if($i != -1){
            Cache::store('redis')->tags(['payGoodsMoneyPerIp'])->forget($key);
            return $this->failed('用户过多，请稍后重试');
        }
        
        $dataJson = json_encode($param);
        $dataKey = md5($dataJson . $randstr);
        Cache::store('redis')->tags(['payDataPer'])->add($dataKey, $dataJson, \Carbon\Carbon::parse(date('Y-m-d H:i:s', $exp)));
        $param['datakey'] = $dataKey;
        return $this->successful(['param' => $param, 'refreshclose' => 1]);
    }
    
    /**
     * 创建order
     * @return type
     */
    public function addOrder()
    {   return $this->failed('参数错误',$this->request->all());
        $request = $this->request;
        if (true !== $validator = $this->validateAddOrder($request->all())) {
            return $validator;
        }

        $time = time();
        $guestuid = $request->post('guestuid', '0');
        $randstr = $request->post('randstr', 'D#@*&U');
        $datakey = $request->post('datakey', 'F*K$KW');
        $oprice = FI($request->post('price','999999'));
        $ip = $this->request->ip();
        $key = md5($ip . $guestuid);
        if($key != Cache::store('redis')->tags(['payGoodsMoneyPer'])->get($oprice)){
            return $this->clearCache($oprice, $datakey);
        }
        $dataJson = Cache::store('redis')->tags(['payDataPer'])->get($datakey);
        if(!$dataJson) {
            return $this->clearCache($oprice, $datakey);
        }
        $dataJson = json_decode ($dataJson, true);
        if($oprice != $dataJson['order_amount']) {
            return $this->clearCache($oprice, $datakey);
        }
                
        $goodsId = $request->post('goodsId');
        $attrId = $request->post('attrId');

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
            $param['u_id'] = FI($request->post('guestuid'));//游客id
            $param['mobile'] = FS($request->post('mobile'));
            if(!$param['mobile'] || !$param['u_id']){
                return $this->failed('参数错误');
            }
        }
        
        $param['order_sn'] = date('YmdHis').getRandStr(6,3);
        $param['order_status'] = 0;
        $param['pay_status'] = 0;
        
        //获取商品
        $goodsModel = new Goods();
        $goods = $goodsModel->getGoods($goodsId);
        if(!$goods || FI($goods->is_on_sale) != 1){
            return $this->failed('商品已下架');
        }
        if($goods->type == 1){
            $attr = $goodsModel->getGoodsAttr($attrId);
            if(!$attr || FI($attr->goods_id) != $goodsId){
                return $this->failed('参数错误');
            }
            if(FI($attr->num) < $num){
                return $this->failed('库存不足');
            }
            $price = FI($attr->attr_price);
        }else if($goods->type == 2){
            //套餐
            if(FI($goods->store_count) < $num){
                return $this->failed('库存不足');
            }
            $price = FI($goods->shop_price);
        }else{
            return $this->failed('参数错误');
        }
        
        $param['type'] = $goods->type;
        $param['ids'] = $goods->ids;
        $param['add_time'] = $time;
        $exp = $param['add_time'] + 5*60;
        $param['goods_price'] = $price;//商品价格
        $param['total_amount'] = $price*$num;//订单总价
        $newPrice = $param['order_amount'] = $param['total_amount'];//支付=订单总价-积分-随机立减
        if(abs(FI($newPrice)-$oprice) >= 100) return $this->failed('参数错误');
        $newPrice = $oprice;
        
        if(!Cache::store('redis')->tags(['payGoodsMoney'])->add($newPrice, '1', \Carbon\Carbon::parse(date('Y-m-d H:i:s', $exp)))) return $this->failed('参数错误');
        $param['discount_money'] = $param['order_amount'] - $newPrice;
        $param['order_amount'] = $newPrice;

        $pay = new Pay();
        $money = $pay->getMoney(1);
        if(in_array($newPrice, $money)){
            //Cache::store('redis')->tags(['payGoodsMoney'])->forget($newPrice);
            return $this->failed('用户过多，请稍后重试');
        }
        
        //先获取二维码
        $file = new File();
        $qrcode = $file->payFileCopy($newPrice, 1);
        if(!$qrcode){
            return $this->failed('系统繁忙，请稍后重试');
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
            'spec_key' => $goods->type == 1 ? $attr->attr : '套餐',
            'goods_type' => $param['type'],
        ];
        
        $payParam = [
            'o_id' => 0,
            'money' => $param['order_amount'],
            'type' => $param['type'],
            'create_time' => $param['add_time'],
            'expiring' => $exp,
            'phone' => $param['mobile'],
            'ip' => get_real_ip(),
            'u_id' => $param['u_id'],
        ];

        //价格已计算好
        $order = new OrderService();
        if(false === $orderId = $order->createOrder($param, $goodsParam, $payParam)){
            return $this->failed($order->getErrorMsg());
        }
        $data = [];
        if (!$user) {
            //返回订单信息
            $data = [
                'order_id' => $orderId,
                'order_amount' => $param['order_amount'],
                'discount_money' => $param['discount_money'],
                'goods_name' => $goods->goods_name,
                'goods_id' => $goods->goods_id,
                'order_sn' => $param['order_sn'],
                'num' => $num,
                'original_img' => $goods->original_img,
                'spec_key' => $goods->type == 1 ? $attr->attr : '套餐'
            ];
        }
        return $this->successful($data);
    }
    
    protected function clearCache($oprice, $datakey){
        Cache::store('redis')->tags(['payGoodsMoneyPer'])->forget($oprice);
        Cache::store('redis')->tags(['payDataPer'])->forget($datakey);
        return $this->failed('数据过期，请刷新重试');
    }
    
    /**
     * 验证order参数
     * @param type $data
     * @return type
     */
    protected function validateAddOrder($data)
    {
        $validator =  Validator::make($data, [
            'goodsId' => "bail|required|integer|min:1",
            'attrId' => "integer|min:1",
            'type' => "bail|required|integer|min:1",
            'city' => "bail|required|string",
            'district' => "bail|required|string",
            'province' => "bail|required|string",
            'address' => "bail|required|string",
            'consignee' => "bail|required|string",
            'num' => "bail|required|string",
            'u_id' => "integer|min:1",
            'mobile' => "regex:'^[1][3,4,5,6,7,8,9][0-9]{9}$'",
        ], [
            'goodsId.required' => '参数错误',
            'goodsId.integer' => '参数错误',
            'attrId.integer' => '参数错误',
            'type.required' => '参数错误',
            'type.integer' => '参数错误',
            'city.required' => '地址错误',
            'district.required' => '地址错误',
            'province.required' => '地址错误',
            'address.required' => '地址错误',
            'consignee.required' => '收货人错误',
            'consignee.integer' => '收货人错误',
            'u_id.integer' => '参数错误',
            'mobile.regex' => '手机错误',
            'num.required' => '数量错误',
            'num.integer' => '数量错误',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return $this->failed($errors[0]);
        }
        return true;
    }
}
