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
    @if (count($goods) > 0)
    @foreach ($goods as $k=>$gg)
    @if (count($gg->home) > 0)
    <div class="main-img mt5 p5"  @if($gg->bg) style="background-color: {{ $gg->bg }}" @endif>
        <div class="main-title">{{ $gg->name }}</div>
        <ul class="grid" id="grid{{ $k }}">
        @foreach ($gg->home as $g)
        <li><div><a href="/goods/detail/{{ $g->goods_id }}"><img  src="{{ $g->original_img }}" alt=""><p class="goods-name m-name">{{ $g->goods_name }}</p>
                    <p class="goods-price">¥{{ price_format($g->shop_price) }}</p></a></div></li>
        @endforeach
        </ul>
    </div>
    @endif
    @endforeach
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
$('.main-img .grid').each(function(i, v){
    $(v).addClass('effect-'+(Math.ceil(Math.random()*7)+1));
    animOnScrollLoad($(v).attr('id'));
});
</script>
@endsection
