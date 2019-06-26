@extends('layouts.app')
@section('title', 'index')
@section('content')
<div class="container">
    <div class="weui-search-bar" id="searchBar">
        <form class="weui-search-bar__form" method="get" action="search">
            <div class="weui-search-bar__box">
                <i class="weui-icon-search"></i>
                <input type="search" class="weui-search-bar__input" id="searchInput" placeholder="搜索">
                <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
            </div>
            <label class="weui-search-bar__label" id="searchText">
                <i class="weui-icon-search"></i>
                <span>搜索</span>
            </label>
        </form>
        <a href="javascript:" class="weui-search-bar__cancel-btn c-gray" id="searchCancel">取消</a>
    </div>
    <div class="swiper-container mt5">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img class="m_mw" src="/r/1.jpg" alt=""></div>
            <div class="swiper-slide"><img class="m_mw" src="/r/2.jpg" alt=""></div>
            <div class="swiper-slide"><img class="m_mw" src="/r/3.jpg" alt=""></div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="main-img mt5 p5">
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefdd对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eee给方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
    </div>
</div>

@endsection
@section('script')
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js"></script>
<script>
      
$(document.body).infinite(200).on("infinite", function() {
    ajax('/index/search',{w:322,ere:332}, function(res){
        console.log(res);
        var html = '<div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>';
        $('.main-img').append(html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html);
        lazyload(".main-img img.lazy");
    },'',0);
    //$(document.body).destroyInfinite()
});

var effect = ['slide','cube', 'coverflow', 'flip'];
$(".swiper-container").swiper({
    autoplay : 3000,
    pagination : '.swiper-pagination',
    paginationClickable :true,
    autoplayDisableOnInteraction : false,
    effect : effect[Math.floor(Math.random()*effect.length)]
});
</script>
@endsection
