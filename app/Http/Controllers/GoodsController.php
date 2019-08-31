<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Service\Goods;
use Illuminate\Support\Facades\Validator;

class GoodsController extends Controller
{
    
    public function goods()
    {
        //获取商品
        $goodsModel = new Goods();
        if($this->request->get('hot', 0) != 1) $goods = $goodsModel->getGoodsList();
        else $goods = $goodsModel->getGoodsHotList();
        return $this->successful($goods);
    }
    
    public function goodsDetail($id)
    {
        //获取商品
        $goodsModel = new Goods();
        $goods = $goodsModel->getGoodsDetail($id);
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
        $goodsModel = new Goods();
        $goods = $goodsModel->getGoodsList(20, $where);
        return $this->successful('goods.search', ['goods' => $goods, 'keywords' => $keywords]);
    }
    
    public function goodsComment($id)
    {
        return $this->successful();
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
