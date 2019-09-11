@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="/static/plugs/pubuliu/css/component.css">
@endsection
@section('title', '搜索')
@section('content')
<div class="container m-top bgc-ec">
    <div class="weui-search-bar" id="searchBar">
        <form class="weui-search-bar__form" method="get" action="/goods/search">
            <div class="weui-search-bar__box">
                <i class="weui-icon-search"></i>
                <input type="search" name="keywords" class="weui-search-bar__input" id="searchInput" placeholder="搜索" value="{{ $keywords }}">
                <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
            </div>
            <label class="weui-search-bar__label" id="searchText">
                <i class="weui-icon-search"></i>
                <span>搜索</span>
            </label>
        </form>
        <a href="javascript:" class="weui-search-bar__cancel-btn c-gray" id="searchCancel">取消</a>
    </div>
    <div class="main-img mt5 p5">
        <ul class="grid" id="grid"> 
        @if (count($list) > 0)
        @foreach ($list as $g)
        <li><div><a href="/goods/detail/{{ $g->goods_id }}"><img  src="{{ $g->original_img }}" alt=""><p class="goods-name m-name">{{ $g->goods_name }}</p></a></div></li>
        @endforeach
        @endif
        </ul>
    </div>
    <div class="m-cl"></div>
</div>
@endsection
@section('script')
<script src="/static/plugs/pubuliu/js/modernizr.custom.js"></script>
<script src="/static/plugs/pubuliu/js/masonry.pkgd.min.js"></script>
<script src="/static/plugs/pubuliu/js/imagesloaded.js"></script>
<script src="/static/plugs/pubuliu/js/classie.js"></script>
<script src="/static/plugs/pubuliu/js/AnimOnScroll.js"></script>
<script>
loadDataMain();
</script>
@endsection
