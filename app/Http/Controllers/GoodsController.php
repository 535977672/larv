<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        return return_ajax(200, 'success', $goods);
    }
    
    public function goodsDetail($id)
    {
        //获取商品
        $goodsModel = new Goods();
        $goods = $goodsModel->getGoodsDetail($id);
        return view('goods.detail', ['goods' => $goods]);
    }
    
    public function search()
    {
        $keywords = $this->request->get('keywords', '');
        $where = [];
        if($keywords) $where[] = ['goods_name', 'like', "%$keywords%"];
        $goodsModel = new Goods();
        $goods = $goodsModel->getGoodsList(20, $where);
        if($this->request->ajax() || $this->request->wantsJson()){
            return return_ajax(200, 'success', $goods);
        }
        return view('goods.search', ['goods' => $goods, 'keywords' => $keywords]);
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
