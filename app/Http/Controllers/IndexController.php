<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Service\Goods;

class IndexController extends Controller
{
    public function index()
    {
        $nav = $this->request->get('nav', 1);
        return view('index.index', ['nav' => $nav]);
    }
    
    public function main()
    {
        //获取商品
        $goodsModel = new Goods();
        $goods = $goodsModel->getGoodsList();
        //轮播 1小时缓存
        $swiper = Cache::store('redis')->remember('index_main_swiper', 60, function () use ($goodsModel) {
            return $goodsModel->getGoodsHotList(5);
        });
        return view('index.main', ['goods' => $goods, 'swiper' => $swiper]);
    }
    
    public function hot()
    {
        $goodsModel = new Goods();
        $goods = $goodsModel->getGoodsHotList();
        return view('index.hot', ['list' => $goods]);
    }
    
    
    
    public function me()
    {
        return view('index.me', ['uid' => \Illuminate\Support\Facades\Auth::id()]);
    }
    
}
