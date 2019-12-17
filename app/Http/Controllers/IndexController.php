<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Service\Goods;
use App\Model\GoodsMenu;

class IndexController extends Controller
{
    public function index()
    {
        $nav = $this->request->get('nav', 1);
        return view('index.index', ['nav' => $nav]);
    }
    
    public function main()
    {
        $swiper = Cache::store('redis')->tags('main')->remember('index_main_swiper', 60, function () {
            $list = GoodsMenu::getGoodsByType('1001');
            return count($list)>0?$list[0]->home:[];
        });
        $goods = Cache::store('redis')->tags('main')->remember('index_main_goods', 80, function ()  {
            return GoodsMenu::getGoodsByType('1002');
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
