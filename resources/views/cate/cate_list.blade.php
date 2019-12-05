@extends('layouts.app')
@section('title', '分类')
@section('content')
<div class="container">
    <div class="weui-search-bar bgc-ec weui-search-bar_focusing" id="searchBar">
        <form class="weui-search-bar__form" method="get" action="/cate/list">
            <div class="weui-search-bar__box">
                <i class="weui-icon-search"></i>
                <input type="search" name="keywords"  class="weui-search-bar__input" id="searchInputs" placeholder="搜索" value="{{ $keywords }}">
            </div>
            <label class="weui-search-bar__label" id="searchTexts">
                <i class="weui-icon-search"></i>
                <span>搜索</span>
            </label>
        </form>
    </div>
    <div class="swiper-container c-cate mt5 p5">
        <div class="swiper-wrapper">
            <div class="swiper-slide" data-sex="0"><div class="c-cate-b c-cate-s">全部</div></div>
            <div class="swiper-slide" data-sex="1"><div class="c-cate-b">男装</div></div>
            <div class="swiper-slide" data-sex="2"><div class="c-cate-b">女装</div></div>
            <div class="swiper-slide" data-sex="3"><div class="c-cate-b">儿童</div></div>
        </div>
    </div>
    <div class="m-cate mt5 p2 m-cl">
        @if (count($list) > 0)
        @foreach ($list as $g)
        <a href="/goods/search?cid={{ $g->id }}">{{ $g->name }}</a>
        @endforeach
        @endif
        <div class="m-cl"></div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js"></script>
<script>
    cate();
</script>
@endsection
