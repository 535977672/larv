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
        //轮播 1小时缓存
        $swiper = Cache::store('redis')->tags('main')->remember('index_main_swiper', 60, function () use ($goodsModel) {
            return $goodsModel->getGoodsList(5, [['in' => ['sex' => '2,4,1']],['is_recommend', '=', 1]], 'goods_id', 'asc', 1);
        });
        $goods1 = Cache::store('redis')->tags('main')->remember('index_main_goods24', 65, function () use ($goodsModel) {
            return $goodsModel->getGoodsList(10, [['in' => ['sex' => '2,4']],['is_recommend', '=', 1]], 'goods_id', 'asc', 1);
        });
        $goods2 = Cache::store('redis')->tags('main')->remember('index_main_goods1', 68, function () use ($goodsModel) {
            return $goodsModel->getGoodsList(10, [['sex', '=', 1],['is_recommend', '=', 1]], 'goods_id', 'asc', 1);
        });
        $goods3 = Cache::store('redis')->tags('main')->remember('index_main_goods3', 70, function () use ($goodsModel) {
            return $goodsModel->getGoodsList(10, [['sex', '=', 3],['is_recommend', '=', 1]], 'goods_id', 'asc', 1);
        });
        return view('index.main', ['goods1' => $goods1,'goods2' => $goods2,'goods3' => $goods3, 'swiper' => $swiper]);
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
