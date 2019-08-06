@extends('layouts.app')
@section('title', 'index')
@section('content')
<div class="container">
    <div class="swiper-container mt2">
        <div class="swiper-wrapper">
            @foreach ($goods->ext->image_url as $i)
            <div class="swiper-slide"><img class="m_mw" src="{{ $i['preview'] }}" alt="" style="width:100%"></div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="mt5 p5 m-goods">
        <div class="goods-price">
            <i class="price-symbol">¥</i>
            <span class="price">{{ price_format($goods->shop_price) }}</span>
            <span class="price-icon">心动价</span>
        </div>
        <div class="goods-name m-name">{{ $goods->goods_name }}</div>
        <div class="goods-adds">
            <span>快递 0.00</span>
            <span>月销量 {{ $goods->sales_sum }}件</span>
            <span>广东佛山{{ $goods->addr }}</span>
        </div>
        @if ($goods->give_integral > 0)
        <div class="goods-prom pt5  f-12">
            <span class="c-888 mr5">促销</span>
            <span class="c-red f-10">积分</span>
            <span>会员购买可得{{ $goods->give_integral }}积分</span>
        </div>
        @endif
        <div class="goods-sku  f-12">
            <div class="skuText">
                <span class="c-888 mr5">选择</span>
                <span class="mr5">请选择样式</span>
                <span class="m-fr svg"><embed src="/static/img/jt.svg" type="image/svg+xml" /></span>
            </div>
            <div class="skuAttr">
                <span class="c-888 mr5">参数</span>
                <span class="mr5">品牌 属性...</span>
                <span class="m-fr svg"><embed src="/static/img/jt.svg" type="image/svg+xml" /></span>
            </div>
        </div>
        <div class="mui-tagscloud-main  f-12">
            <div class="mui-tagscloud-title">
              商品评价 ({{ $goods->comment_count }})
                <div class="m-fr c-ccc">查看全部<span class="svg"><embed src="/static/img/jt.svg" type="image/svg+xml" /></span></div>
            </div>
        </div>
        <div class="mui-tagscloud-main">
            <div class="c-888 t-ac f-10 mb10">
              商品详情
            </div>
            <div class="goods-content f-14">
                {!! $goods->ext->content !!}
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js"></script>
<script>
$(".swiper-container").swiper({
    autoplay : 3000,
    pagination : '.swiper-pagination',
    paginationClickable :true,
    autoplayDisableOnInteraction : false,
    effect : 'slide'
});
</script>
@endsection
