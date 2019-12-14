@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="/static/plugs/pubuliu/css/component.css">
@endsection
@section('title', '首页')
@section('content')
<div class="container m-top bgc-ec">
    <div class="weui-search-bar bgc-ec" id="searchBar">
        <form class="weui-search-bar__form" method="get" action="/goods/search">
            <div class="weui-search-bar__box">
                <i class="weui-icon-search"></i>
                <input type="search" name="keywords" class="weui-search-bar__input" id="searchInput" placeholder="搜索">
                <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
            </div>
            <label class="weui-search-bar__label" id="searchText">
                <i class="weui-icon-search"></i>
                <span>搜索</span>
            </label>
        </form>
        <a href="javascript:" class="weui-search-bar__cancel-btn c-gray" id="searchCancel">取消</a>
    </div>
    @if (count($swiper) > 0)
    <div class="swiper-container mt5">
        <div class="swiper-wrapper">
            @foreach ($swiper as $g)
            <div class="swiper-slide"><a href="/goods/detail/{{ $g->goods_id }}"><img class="m_mw" src="{{ $g->original_img }}" alt=""></a></div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
    @endif       
    @if (count($goods1) > 0)
    <div class="main-img bgc-1 mt5 p5">
        <div class="main-title">女装</div>
        <ul class="grid" id="grid1"> 
        @foreach ($goods1 as $g)
        <li><div><a href="/goods/detail/{{ $g->goods_id }}"><img  src="{{ $g->original_img }}" alt=""><p class="goods-name m-name">{{ $g->goods_name }}</p>
                    <p class="goods-price">¥{{ price_format($g->shop_price) }}</p></a></div></li>
        @endforeach
        </ul>
    </div>
    @endif     
    @if (count($goods2) > 0)
    <div class="main-img bgc-2 mt5 p5">
        <div class="main-title">男装</div>
        <ul class="grid" id="grid2"> 
        @foreach ($goods2 as $g)
        <li><div><a href="/goods/detail/{{ $g->goods_id }}"><img  src="{{ $g->original_img }}" alt=""><p class="goods-name m-name">{{ $g->goods_name }}</p>
                    <p class="goods-price">¥{{ price_format($g->shop_price) }}</p></a></div></li>
        @endforeach
        </ul>
    </div>
    @endif 
    @if (count($goods3) > 0)
    <div class="main-img bgc-3 mt5 p5">
        <div class="main-title">童装</div>
        <ul class="grid" id="grid3"> 
        @foreach ($goods3 as $g)
        <li><div><a href="/goods/detail/{{ $g->goods_id }}"><img  src="{{ $g->original_img }}" alt=""><p class="goods-name m-name">{{ $g->goods_name }}</p>
                    <p class="goods-price">¥{{ price_format($g->shop_price) }}</p></a></div></li>
        @endforeach
        </ul>
    </div>
    @endif
    <div class="m-cl"></div>
</div>
@endsection
@section('script')
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js"></script>
<script src="/static/plugs/pubuliu/js/modernizr.custom.js"></script>
<script src="/static/plugs/pubuliu/js/masonry.pkgd.min.js"></script>
<script src="/static/plugs/pubuliu/js/imagesloaded.js"></script>
<script src="/static/plugs/pubuliu/js/classie.js"></script>
<script src="/static/plugs/pubuliu/js/AnimOnScroll.js"></script>
<script>
var effect = ['slide','cube', 'coverflow', 'flip'];
$(".swiper-container").swiper({
    autoplay : 3000,
    pagination : '.swiper-pagination',
    paginationClickable :true,
    autoplayDisableOnInteraction : false,
    effect : effect[Math.floor(Math.random()*effect.length)],
    cube: {
        slideShadows: false,
        shadow: false
    }
});
$('#grid1').addClass('effect-'+(Math.ceil(Math.random()*7)+1));
$('#grid2').addClass('effect-'+(Math.ceil(Math.random()*7)+1));
$('#grid3').addClass('effect-'+(Math.ceil(Math.random()*7)+1));
animOnScrollLoad('grid1');
animOnScrollLoad('grid2');
animOnScrollLoad('grid3');
</script>
@endsection
