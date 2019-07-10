@extends('layouts.app')
@section('title', 'index')
@section('content')
<div class="layui-container">
    <div class="weui-search-bar" id="searchBar">
        <form class="weui-search-bar__form" method="get" action="search">
            <div class="weui-search-bar__box">
                <i class="weui-icon-search"></i>
                <input type="search" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required="">
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
        <div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/zfb10.png" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>
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
<div class="weui-loadmore  m-cl">
    <i class="weui-loading"></i>
    <span class="weui-loadmore__tips">小编正在努力...</span>
</div>
@endsection
@section('script')
<script>
    
var loadings = false;      
$(document.body).infinite(200).on("infinite", function() {
    if(loadings) return;
    loadings = true;
    ajax('/index/search',{w:322,ere:332}, function(res){
        console.log(res);
        var html = '<div class="m-fl"><div><img  class="lazy" src="/static/img/bg.jpg" data-original="/static/img/2.jpg" alt=""><p class="m-name">eeeeefddfg发光飞碟刚发给对方32454b刚发给对方刚发给对方ccv</p></div></div>';
        $('.main-img').append(html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html+html);
        lazyload(".main-img img.lazy");
        loadings = false;
    },'',0,1);
    //$(document.body).destroyInfinite()
});

</script>
@endsection
