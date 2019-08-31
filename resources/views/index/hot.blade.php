@extends('layouts.app')
@section('title', '热门')
@section('content')
<div class="container">
    <div class="weui-search-bar" id="searchBar">
        <form class="weui-search-bar__form" method="get" action="/goods/search">
            <div class="weui-search-bar__box">
                <i class="weui-icon-search"></i>
                <input type="search" name="keywords"  class="weui-search-bar__input" id="searchInput" placeholder="搜索">
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
        @if (count($goods) > 0)
        @foreach ($goods as $g)
        <div class="m-fl"><div><a href="/goods/detail/{{ $g->goods_id }}"><img  class="lazy" src="/static/img/bg.jpg" data-original="{{ $g->original_img }}" alt=""><p class="m-name">{{ $g->goods_name }}</p></a></a></div></div>
        @endforeach
        @endif
    </div>
    <div class="m-cl"></div>
</div>

@endsection
@section('script')
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js"></script>
<script>
var loadings = false;
var next_page_url = '/goods?page=2';
$(document.body).infinite(200).on("infinite", function() {
    if(loadings || isEmpty(next_page_url)) return;
    loadings = true;
    ajax(next_page_url,{hot: 1}, function(res){
        if(res.status !== 200){
            loadings = false;
            $.toast(res.msg, "cancel");
            return;
        }
        var goods = res.data.data;
        if(goods.length < 1 || isEmpty(next_page_url)){
            $(document.body).destroyInfinite();
        }
        next_page_url = res.data.next_page_url;
        var html = '';
        $.each(goods, function(i, g){
            html = html + '<div class="m-fl"><div><a href="/goods/detail/'+g.goods_id+'"><img  class="lazy" src="/static/img/bg.jpg" data-original="'+g.original_img+'" alt=""><p class="m-name">'+g.goods_name+'</p></a></a></div></div>';
        });
        $('.main-img').append(html);
        lazyload(".main-img img.lazy");
        loadings = false;
    },'GET', 0, 1);
});
</script>
@endsection
