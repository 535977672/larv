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
     * @param type $num 1-10
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
            return $this->failed('操作频繁,请稍后再试');
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
            $param['attr_id'] = 0;
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
                    'goods_name' => $v->goods_name,
                    'goods_id' => $v->goods_id,
                    'attr_id' => $v->attr_id,
                    'attr_price' => $v->attr_price,
                    'num' => $num,
                    'total' => bcmul($num, attr_price)
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
            $param['num'] = $num;
            $param['total'] = bcmul($num, $oprice);
        }
        if($oprice != $price){
            return $this->failed('价格变动,请重新下单');
        }
        $param['uaddr'] = ['consignee' => '', 'province' => '', 'city' => '', 'district' => '', 'address' => '', 'mobile' => ''];
        $user = $this->request->user();
        if ($user) {
            //收货地址
            $addr = UserAddress::where('u_id', $user->id)->first();
            if($addr) $param['uaddr'] = $addr->toArray();
        }
        $param['total_num'] = $num;
        $param['order_amount'] = bcmul($num, $oprice);//订单总价
        $newPrice = intval($param['order_amount']);//支付=订单总价-积分-随机立减
        
        //redis控制价格唯一
        $i = 15;
        $srand = 1;
        $erand = 49;
        $mark = 5;
        while($i > 0){
            if(Cache::store('redis')->tags(['payGoodsMoney'])->get($newPrice)){
            }else if(Cache::store('redis')->tags(['payGoodsMoneyPer'])->add($newPrice, $key, \Carbon\Carbon::parse(date('Y-m-d H:i:s', $exp)))){
                $pay = new Pay();
                $money = $pay->getMoney();
                if(!in_array($newPrice, $money)){
                    $param['discount_money'] = bcsub($param['order_amount'], $newPrice);
                    $param['order_amount'] = $newPrice;
                    $i = -2;
                }
            }
            if($i < $mark && $srand < 50){
                $srand = 50;
                $erand = 99;
            }
            if($i != -2){
                $newPrice = bcsub($param['order_amount'], mt_rand($srand, $erand));
                $i--;
            }
        }
        if($i != -2){
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
    {   
        $request = $this->request;
        if (true !== $validator = $this->validateAddOrder($request->all())) {
            return $validator;
        }

        $time = time();
        $guestuid = $request->post('guestuid', '0');
        $randstr = $request->post('randstr', 'D#@*&U');
        $datakey = $request->post('datakey', 'F*K$KW');
        $oprice = FI($request->post('price','99999999'));
        $code = FI($request->post('code'));
        $ip = $this->request->ip();
        $key = md5($ip . $guestuid);
        if($key != Cache::store('redis')->tags(['payGoodsMoneyPer'])->get($oprice)){
            return $this->clearCache($oprice, $datakey, $code);
        }
        $dataJson = Cache::store('redis')->tags(['payDataPer'])->get($datakey);
        if(!$dataJson) {
            return $this->clearCache($oprice, $datakey, $code);
        }
        $dataJson = json_decode ($dataJson, true);
        if($oprice != $dataJson['order_amount']) {
            return $this->clearCache($oprice, $datakey, $code);
        }
        $this->clearCache($oprice, $datakey, $code);

        $uaddr = $request->post('uaddr', '');
        $uaddr = explode(' ', $uaddr);
        if(!$uaddr[0] || !$uaddr[1] || !$uaddr[2]) return $this->failed('地址选择错误');
        $param['city'] = $uaddr[1];
        $param['district'] = $uaddr[2];
        $param['province'] = $uaddr[0];
        $param['address'] = $request->post('address');
        $param['consignee'] = $request->post('consignee');
        $user = $request->user();
        if ($user) {
            $param['u_id'] = $user->id;
            $param['mobile'] = FS($request->post('mobile'));
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
        if(!isset($dataJson['attrs'])){
            $dataJson['attrs'][] = $dataJson;
        }
        $total_amount = 0;
        $goodsModel = new Goods();
        foreach($dataJson['attrs'] as $p){
            $goodsId = $p['goods_id'];
            $attrId = $p['attr_id'];
            $num = $p['num'];

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
            $spec_key = '';
            $goodsParam = [];
            $total_amount = bcadd($total_amount, bcmul($price, $num));
            if($goods->type == 1){
                if($p['colorname'] && $p['attr']) $spec_key = $p['colorname'].'-'.$p['attr'];
                else $spec_key = $p['colorname']?:$p['attr'];
                $ourl = $goodsModel->getGoodsExt($goodsId)->original_url;
                $goodsParam[] = [
                    'order_id' => 0,
                    'goods_id' => $goods->goods_id,
                    'goods_name' => $goods->goods_name,
                    'goods_sn' => $param['order_sn'],
                    'goods_num' => $num,
                    'goods_price' => $goods->shop_price,
                    'final_price' => $p['total'],
                    'arrt_id' => $attrId,
                    'spec_key' => $spec_key,
                    'goods_type' => $goods->type,
                    'img' => $p['img'],
                    'o_url' => $ourl,
                ];
            }else{
                $subgoods = $dataJson['sub'];
                if(!$subgoods) return $this->failed('参数错误');
                $goodsParam = [];
                foreach ($subgoods as $v){
                    if($v['colorname'] && $v['attr']) $spec_key = $v['colorname'].'-'.$v['attr'];
                    else $spec_key = $v['colorname']?:$v['attr'];
                    $goodsParam[] = [
                        'order_id' => 0,
                        'goods_id' => $v['goods_id'],
                        'goods_name' => $v['goods_name'],
                        'goods_sn' => $param['order_sn'],
                        'goods_num' => $num,
                        'goods_price' => $v['attr_price'],
                        'final_price' => '0.00',
                        'arrt_id' => $v['attr_id'],
                        'spec_key' => $spec_key,
                        'goods_type' => 2,
                        'img' => $v['img'],
                        'o_url' => $goodsModel->getGoodsExt($v['goods_id'])->original_url,
                    ];
                }
            }

        }
//        $goods = $goodsModel->getGoods($goodsId);
//        if(!$goods || FI($goods->is_on_sale) != 1){
//            return $this->failed('商品已下架');
//        }
//        if($goods->type == 1){
//            $attr = $goodsModel->getGoodsAttr($attrId);
//            if(!$attr || FI($attr->goods_id) != $goodsId){
//                return $this->failed('参数错误');
//            }
//            if(FI($attr->num) < $num){
//                return $this->failed('库存不足');
//            }
//            $price = FI($attr->attr_price);
//        }else if($goods->type == 2){
//            //套餐
//            if(FI($goods->store_count) < $num){
//                return $this->failed('库存不足');
//            }
//            $price = FI($goods->shop_price);
//        }else{
//            return $this->failed('参数错误');
//        }
        
        $param['paytype'] = 1;
        $param['add_time'] = $time;
        $exp = $param['add_time'] + 5*60;
        $param['goods_price'] = 0;//商品价格
        $param['total_amount'] = $total_amount;//订单总价
        $newPrice = $param['order_amount'] = $param['total_amount'];//支付=订单总价-积分-随机立减
        if(abs(FI($newPrice)-$oprice) > 200) return $this->failed('参数错误');
        $newPrice = $oprice;
        
        if(!Cache::store('redis')->tags(['payGoodsMoney'])->add($newPrice, '1', \Carbon\Carbon::parse(date('Y-m-d H:i:s', $exp)))) return $this->failed('参数错误，请刷新重试', [], $code);
        $param['discount_money'] = $param['order_amount'] - $newPrice;
        $param['order_amount'] = $newPrice;

        $pay = new Pay();
        $money = $pay->getMoney();
        if(in_array($newPrice, $money)){
            //Cache::store('redis')->tags(['payGoodsMoney'])->forget($newPrice);
            return $this->failed('用户过多，请刷新重试', [], $code);
        }
        
        //先获取二维码
        $file = new File();
        //$qrcode = $file->payFileCopy($newPrice, $param['paytype']);
        $qrcode = $file->payFileWaterMark($newPrice, $exp, $param['paytype']);
        if(!$qrcode){
            return $this->failed('系统繁忙，请刷新重试', [], $code);
        }
//        $spec_key = '';
//        if($goods->type == 1){
//            if($dataJson['colorname'] && $dataJson['attr']) $spec_key = $dataJson['colorname'].'-'.$dataJson['attr'];
//            else $spec_key = $dataJson['colorname']?:$dataJson['attr'];
//            $ourl = $goodsModel->getGoodsExt($goodsId)->original_url;
//            $goodsParam = [
//                'order_id' => 0,
//                'goods_id' => $goods->goods_id,
//                'goods_name' => $goods->goods_name,
//                'goods_sn' => $param['order_sn'],
//                'goods_num' => $num,
//                'goods_price' => $param['goods_price'],
//                'final_price' => $param['order_amount'],
//                'arrt_id' => $attrId,
//                'spec_key' => $spec_key,
//                'goods_type' => $param['type'],
//                'img' => $dataJson['img'],
//                'o_url' => $ourl,
//            ];
//        }else{
//            $subgoods = $dataJson['sub'];
//            if(!$subgoods) return $this->failed('参数错误');
//            $goodsParam = [];
//            foreach ($subgoods as $v){
//                if($v['colorname'] && $v['attr']) $spec_key = $v['colorname'].'-'.$v['attr'];
//                else $spec_key = $v['colorname']?:$v['attr'];
//                $goodsParam[] = [
//                    'order_id' => 0,
//                    'goods_id' => $v['goods_id'],
//                    'goods_name' => $v['goods_name'],
//                    'goods_sn' => $param['order_sn'],
//                    'goods_num' => $num,
//                    'goods_price' => $v['attr_price'],
//                    'final_price' => '0.00',
//                    'arrt_id' => $v['attr_id'],
//                    'spec_key' => $spec_key,
//                    'goods_type' => 2,
//                    'img' => $v['img'],
//                    'o_url' => $goodsModel->getGoodsExt($v['goods_id'])->original_url,
//                ];
//            }
//        }
        $payParam = [
            'o_id' => 0,
            'money' => $param['order_amount'],
            'type' => $param['paytype'],
            'create_time' => $param['add_time'],
            'expiring' => $exp,
            'phone' => $param['mobile'],
            'ip' => get_real_ip(),
            'u_id' => $param['u_id'],
            'qrcode' => $qrcode,
        ];

        //价格已计算好
        $order = new OrderService();
        if(false === $orderId = $order->createOrder($param, $goodsParam, $payParam)){
            return $this->failed($order->getErrorMsg(), [], $code);
        }
        if ($user) {
            UserAddress::create($param);
        }
        $data = ['order_id' => $orderId];
        if (!$user) {
            //返回订单信息
            $data = [
                'order_id' => $orderId,
                'goods_name' => $goods->goods_name,
                'order_sn' => $param['order_sn'],
                'num' => $num,
                'add_time' => date('m-d H:i', $param['add_time']),
            ];
            foreach ($goodsParam as $v) {
               $data['ordergoods'][] = [
                    'goods_id' => $v['goods_id'],
                    'goods_name' => $v['goods_name'],
                    'goods_num' => $v['goods_num'],
                    'spec_key' => $v['spec_key'],
                    'img' => $v['img']
                ];
            }
        }
        return $this->successful($data);
    }

    /**
     * 购物车
     */
    public function orderRequestCart(){
        $attrs = $this->request->post('attr', []);
        $guestuid = $this->request->post('guestuid', []);
        if(!$attrs) return $this->failed('没有商品数据');

        $time = time();
        $exp = $time + 5*60;
        $ip = $this->request->ip();
        $key = md5($ip . $guestuid);
        $randstr = getRandStr();
        if(!Cache::store('redis')->tags(['payGoodsMoneyPerIp'])->add($key, 1, \Carbon\Carbon::parse(date('Y-m-d H:i:s', $time+20)))){
            return $this->failed('操作频繁,请稍后再试');
        }
        $param = compact("randstr", "time", "guestuid");

        $goodsModel = new Goods();
        $total_order_amount = 0;//订单总价
        $total_num = 0;//总数量
        foreach($attrs as $a){
            if($a['num'] < 1)  return $this->clearCartCache($key,$a['name'] . '数量要大于1件');
            if($a['type'] != 1) return $this->clearCartCache($key,$a['name'] . '参数错误');
            $total_num = bcadd($total_num, $a['num']);
            $attrId = $a['cart_id'];
            $attr = $goodsModel->getGoodsAttr($attrId);
            if(!$attr) return $this->clearCartCache($key,'商品已下架');
            if(FI($attr->num) < $a['num']) return $this->clearCartCache($key,'库存不足');
            $oprice = FI($attr->attr_price);
            $goodsId = $attr->goods_id;
            if($oprice != $a['price']){
                return $this->clearCartCache($key,$a['name'] . '价格变动,请重新下单');
            }
            $total = bcmul($a['num'], $oprice);
            if($total != $a['total']){
                return $this->clearCartCache($key,$a['name'] . '价格变动,请重新下单');
            }
            $total_order_amount = bcadd($total_order_amount, $total);
            $color = '';
            if($attr->color_id>0){
                $color = $goodsModel->getGoodsColor($attr->color_id);
            }

            $goods = $goodsModel->getGoods($goodsId);
            if(!$goods || $goods->is_on_sale != 1) return $this->failed('商品已下架');
            $p['goods_name'] = $goods->goods_name;

            $attrname = $colorname = $img = '';
            if($attr->img) $img = $attr->img;
            else if($color && $color->img) $img = $color->img;
            else $img = $goods->original_img;
            if($attr->attr) $attrname = $attr->attr;
            if($color && $color->color) $colorname = $color->color;
            $p['type'] = 1;
            $p['attr_id'] = $attrId;
            $p['img'] = $img;
            $p['attr'] = $attrname;
            $p['colorname'] = $colorname;
            $p['color_id'] = $attr->color_id;
            $p['goods_id'] = $goodsId;
            $p['num'] = $a['num'];
            $p['total'] = $total;
            $param['attrs'][] = $p;
        }
        $param['uaddr'] = ['consignee' => '', 'province' => '', 'city' => '', 'district' => '', 'address' => '', 'mobile' => ''];
        $user = $this->request->user();
        if ($user) {
            //收货地址
            $addr = UserAddress::where('u_id', $user->id)->first();
            if($addr) $param['uaddr'] = $addr->toArray();
        }
        $param['total_num'] = $total_num;
        $param['order_amount'] = $total_order_amount;//订单总价
        $newPrice = intval($param['order_amount']);//支付=订单总价-积分-随机立减

        //redis控制价格唯一
        $i = 30;
        $srand = 1;
        $erand = 100;
        $mark = 10;
        while($i > 0){
            if(Cache::store('redis')->tags(['payGoodsMoney'])->get($newPrice)){
            }else if(Cache::store('redis')->tags(['payGoodsMoneyPer'])->add($newPrice, $key, \Carbon\Carbon::parse(date('Y-m-d H:i:s', $exp)))){
                $pay = new Pay();
                $money = $pay->getMoney();
                if(!in_array($newPrice, $money)){
                    $param['discount_money'] = bcsub($param['order_amount'], $newPrice);
                    $param['order_amount'] = $newPrice;
                    $i = -2;
                }
            }
            if($i < $mark && $srand < 100){
                $srand = $erand+1;
                $erand = $erand*2;
            }
            if($i != -2){
                $newPrice = bcsub($param['order_amount'], mt_rand($srand, $erand));
                $i--;
            }
        }
        if($i != -2){
            return $this->clearCartCache($key,'用户过多，请稍后重试');
        }

        $dataJson = json_encode($param);
        $dataKey = md5($dataJson . $randstr);
        Cache::store('redis')->tags(['payDataPer'])->add($dataKey, $dataJson, \Carbon\Carbon::parse(date('Y-m-d H:i:s', $exp)));
        return $this->successful(['datakey' => $dataKey]);
    }

    /**
     * 购物车确认
     * @param $datakey
     */
    public function orderRequestCartData($datakey){
        $dataJson = Cache::store('redis')->tags(['payDataPer'])->get($datakey);
        if(!$dataJson) {
            return $this->failed('商品信息已过期');
        }
        $param = json_decode ($dataJson, true);
        $param['datakey'] = $datakey;
        return $this->successful(['param' => $param]);
    }
    /**
     * 显示支付信息
     */
    public function payOrder($id)
    {
        $pay = new \App\Service\Pay;
        $record = $pay->getPayRecordByoId($id);
        if(!$record) return $this->failed('订单信息不存在');
        $type = $record->type;
        $exp = $record->expiring;
        $qrcode = $record->qrcode;
        $money = price_format($record->money);
        $oid = $record->o_id;
        $code = \App\Model\Order::find($id)->order_sn;
        if(in_array($record->status, [3,4,5])) return $this->failed('已支付');
        else if($record->status != 0) return $this->failed('请求已过期');
        $d = $exp - time();
        if($d > 300 || $d < 0) return $this->failed('请求已过期');
        return $this->successful(compact('qrcode', 'type', 'exp', 'money', 'oid', 'code'));
    }
    
    /**
     * 显示支付信息
     */
    public function payCheck()
    {
        $id = $this->request->post('oid', 0);
        $pay = new \App\Service\Pay;
        $record = $pay->getPayRecordByoId($id);
        if(!$record || !in_array($record->status, [3,4,5])) return $this->failed();
        return $this->successful();
    }
    
    public function orderList()
    {
        $user = $this->request->user();
        if ($user) {
            $order = new OrderService();
            $list = $order->getOrderList($user->id);
            foreach ($list as $v){
                $v->add_time = date('m-d H:i', $v->add_time);
                $v->statusstr = $v->getOrderStatusStrAttribute();
            }
            return $this->successful('', ['list' => $list]);
        }
        return $this->successful();
    }
    
    public function orderDetail($id, $guestuid)
    {
        $user = $this->request->user();
        if ($user) {
            $guestuid = $user->id;
        }
        $order = new OrderService();
        $detail = $order->getOrderDetail($id);
        if(!$detail || $detail->u_id != $guestuid) return $this->failed('请求错误');
        if($detail->deleted) return $this->failed('订单已删除');
        return $this->successful('', compact('detail'));
    }
    
    public function orderDel($id)
    {
        $user = $this->request->user();
        $guestuid = $user?$user->id:$this->request->post('guestuid', 0);
        $order = new OrderService();
        if(!$order->orderDel($id, $guestuid)) return $this->failed('删除失败');
        return $this->successful('删除成功');
    }
    
    public function orderQuest($id)
    {
        $user = $this->request->user();
        $guestuid = $user?$user->id:$this->request->post('guestuid', 0);
        $order = new OrderService();
        if(!$order->orderQuest($id, $guestuid)) return $this->failed($order->getErrorMsg());
        return $this->successful('确认收货成功');
    }
    
    protected function clearCache($oprice, $datakey, $code = 400){
        Cache::store('redis')->tags(['payGoodsMoneyPer'])->forget($oprice);
        Cache::store('redis')->tags(['payDataPer'])->forget($datakey);
        return $this->failed('数据过期，请刷新重试', [], $code);
    }

    protected function clearCartCache($key, $msg = ''){
        Cache::store('redis')->tags(['payGoodsMoneyPerIp'])->forget($key);
        return $this->failed($msg);
    }
    
    /**
     * 验证order参数
     * @param type $data
     * @return type
     */
    protected function validateAddOrder($data)
    {
        $validator =  Validator::make($data, [
            'address' => "bail|required|string",
            'uaddr' => "bail|required|string",
            'consignee' => "bail|required|string",
            'mobile' => "bail|required|regex:'^[1][3,4,5,6,7,8,9][0-9]{9}$'",
            'price' => "bail|required|integer",
            'guestuid' =>  "bail|required|regex:'[0-9]{10}'",
            'randstr' => "bail|required|string",
            'datakey' => "bail|required|string",
        ], [
            'address.required' => '地址错误',
            'uaddr.required' => '地址错误',
            'consignee.required' => '收货人错误',
            'consignee.integer' => '收货人错误',
            'mobile.regex' => '手机错误',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return $this->failed($errors[0]);
        }
        return true;
    }

    /**
     * 购物车列表
     */
    public function orderCart()
    {
        return $this->successful();
    }
}
