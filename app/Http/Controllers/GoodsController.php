<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Service\Goods;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class GoodsController extends Controller
{
    private $goodsModel = '';

    protected function initialize() 
    { 
        $this->goodsModel = new Goods();
    }
    
    public function goods()
    {
        //获取商品
        if($this->request->get('hot', 0) != 1) $goods = $this->goodsModel->getGoodsList();
        else $goods = $this->goodsModel->getGoodsHotList();
        return $this->successful('', ['list' => $goods]);
    }
    
    public function goodsDetail($id)
    {
        //获取商品
        $goods = $this->goodsModel->getGoodsDetail($id);
        if(!$goods){
            return $this->failed('商品已删除');
        }
        if($goods->type == 2) return $this->successful('goods.goods_detail_2', ['goods' => $goods]);
        return $this->successful('', ['goods' => $goods]);
    }
    
    public function search()
    {
        $keywords = $this->request->get('keywords', '');
        $cid = $this->request->get('cid', 0);
        $sex = $this->request->get('sex', 0);
        $hot = $this->request->get('hot', 0);
        $order = $this->request->get('order', 'goods_id');
        $asc = $this->request->get('asc', 0);

        $random = $this->request->get('random', 0);//是否随机
        $limit = $this->request->get('limit', 20);
        $limit = $random?$limit:20;
        $limit = $limit<=20?$limit:20;

        $where = [];
        if($keywords) $where[] = ['goods_name', 'like', "%$keywords%"];
        if($cid) $where[] = ['cid', '=', $cid];
        if($sex) $where[] = ['sex', '=', $sex];
        if($hot) $where[] = ['hot', '=', $hot];
        $key = md5('goods_search_'.$limit.$order.$asc.$random.json_encode($where));
        $goods = Cache::remember($key, 60*23, function () use ($limit, $where, $order, $asc, $random) {
            return (new Goods())->getGoodsList($limit, $where, $order, $asc, $random);
        });
        return $this->successful('goods.search', ['list' => $goods, 'keywords' => $keywords]);
    }
    
    public function goodsComment($id)
    {
        return $this->successful('', ['list' => $this->goodsModel->getGoodsCommentList(['gid' => $id]), 'id' => $id]);
    }
    
    /**
     * 验证
     * @param type $data
     * @return type
     */
    protected function validateAddOrder($data)
    {
        $validator = Validator::make($data, [
            'goodsId' => "bail|required|integer|min:1",
            'attrId' => "integer|min:1",
        ], [
            'goodsId.required' => '参数错误',
            'goodsId.integer' => '参数错误',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return return_ajax(0, $errors[0]);
        }
        return true;
    }
}
