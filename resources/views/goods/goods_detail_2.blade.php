@extends('layouts.app')
@section('title', '详情')
@section('content')
<div class="container">
    <div class="swiper-container mt2">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img class="m_mw" src="{{ $goods->original_img }}" alt="" style="width:100%"></div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="mt5 m-goods bgc-ec">
        <div class="goods-items goods-price">
            <i class="price-symbol">¥</i>
            <span class="price">{{ price_format($goods->shop_price) }}</span>
            <span class="price-icon">心动价</span>
        </div>
        <div class="goods-items">
            <div class="goods-name m-name">{{ $goods->goods_name }}</div>
            <div class="goods-adds mt10">
                <span>快递 0.00</span>
                <span>月销量 {{ $goods->sales_sum }}件</span>
                <span>{{ $goods->addr }}</span>
            </div>
        </div>
        @if ($goods->is_on_sale == 0)
            <div class="goods-items goods-prom pt5  f-12">
                <span class="c-red f-10">商品已下架</span>
            </div>
        @endif
        @if ($goods->give_integral > 0)
        <div class="goods-items goods-prom pt5  f-12">
            <span class="c-888 mr5">促销</span>
            <span class="c-red f-10">积分</span>
            <span>会员购买可得{{ $goods->give_integral }}积分</span>
        </div>
        @endif
        <div class="goods-items goods-sku">
            <div class="skuText">
                <span class="c-888 mr5">选择</span>
                <span class="mr5">请选择参数</span>
                <span class="m-fr svg"><embed src="/static/img/jt.svg" type="image/svg+xml" /></span>
            </div>
        </div>
        <div class="goods-items">
            <div class="goods-comment">
                商品评价 ({{ $goods->comment_count }})
                <div class="m-fr c-ccc"><a href="/goods/comment/{{ $goods->goods_id }}">查看全部<span class="svg"><embed src="/static/img/jt.svg" type="image/svg+xml" /></span></a></div>
            </div>
        </div>
        <div class="goods-items f-14">
            <div class="c-888 t-ac mb10">
                套餐商品
            </div>
            <div>
                @foreach ($goods->goods as $a)
                <div class="goods-sub-items mt5 m-cl">
                    <img class="m-fl mr5" src="{{ $a->original_img }}">
                    <div class="goods-name m-name">{{ $a->goods_name }}</div>
                    <div><i class="price-symbol">¥ </i>{{ price_format($a->shop_price) }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@if ($goods->is_on_sale > 0)
<div id="skuTextPop" class="weui-popup__container popup-bottom">
    <div class="weui-popup__overlay"></div>
    <div class="weui-popup__modal">
        <div class="m-popup m-popup-text">
            <div class="mt10 mr10 ml10 mb10">
                <div>
                    <div class="m-fl mr10"><img id="select-img" src="{{ $goods->original_img }}" width="60" height="60"></div>
                    <div class="goods-price">
                        <i class="price-symbol">¥</i>
                        <span class="price" id="select-price" data-price="{{ price_format($goods->shop_price) }}">{{ price_format($goods->shop_price) }}</span>
                    </div>
                </div>
                @if ($goods->goods)
                <div class="m-lineb m-cl pt10">
                    <div>套餐商品</div>
                    <div>
                    @foreach ($goods->goods as $a)
                    <div class="goods-sub-items mt5 m-cl">
                        <img class="m-fl mr5" src="{{ $a->original_img }}">
                        <div class="goods-name m-name">{{ $a->goods_name }}</div>
                        <div><i class="price-symbol">¥ </i>{{ price_format($a->shop_price) }}</div>
                    </div>
                    @endforeach
                    </div>
                </div>
                @endif
                <div class="m-lineb m-cl mb10 pt10">
                    <div class="select-num mb10 pt10">
                        <div class="m-fl">购买数量</div>
                        <div class="m-fr"><button id="select-numd">-</button><span class="pl10 pr10" id="select-num">1</span><button id="select-numu">+</button></div>
                    </div>
                </div>
                <div class="m-lineb">
                    <div class="mb10 pt10">
                        <button id="select-buy" data-type="{{ $goods->type }}" data-id="{{ $goods->goods_id }}">立即购买</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('script')
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js"></script>
<script>
    initDetail();
</script>
@endsection
