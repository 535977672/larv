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
        $goods = $goodsModel->getGoodsList();
        return return_ajax(200, 'success', $goods);
    }
    
    public function goodsDetail($id)
    {
        //获取商品
        $goodsModel = new Goods();
        $goods = $goodsModel->getGoodsDetail($id);
        return return_ajax(200, 'success', $goods);
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
