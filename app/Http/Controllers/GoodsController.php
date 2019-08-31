<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Service\Goods;
use Illuminate\Support\Facades\Validator;

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
            return $this->failed();
        }
        return $this->successful('', ['goods' => $goods]);
    }
    
    public function search()
    {
        $keywords = $this->request->get('keywords', '');
        $where = [];
        if($keywords) $where[] = ['goods_name', 'like', "%$keywords%"];
        $goods = $this->goodsModel->getGoodsList(20, $where);
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
