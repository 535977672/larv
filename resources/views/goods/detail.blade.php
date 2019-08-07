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
        <div class="goods-items goods-price">
            <i class="price-symbol">¥</i>
            <span class="price">{{ price_format($goods->shop_price) }}</span>
            <span class="price-icon">心动价</span>
        </div>
        <div class="goods-items goods-name m-name">{{ $goods->goods_name }}</div>
        <div class="goods-items goods-adds">
            <span>快递 0.00</span>
            <span>月销量 {{ $goods->sales_sum }}件</span>
            <span>{{ $goods->addr }}</span>
        </div>
        @if ($goods->give_integral > 0)
        <div class="goods-items goods-prom pt5  f-12">
            <span class="c-888 mr5">促销</span>
            <span class="c-red f-10">积分</span>
            <span>会员购买可得{{ $goods->give_integral }}积分</span>
        </div>
        @endif
        <div class="goods-items goods-sku  f-12">
            <div class="skuText">
                <span class="c-888 mr5">选择</span>
                <span class="mr5">请选择参数</span>
                <span class="m-fr svg"><embed src="/static/img/jt.svg" type="image/svg+xml" /></span>
            </div>
            <div class="skuAttr">
                <span class="c-888 mr5">参数</span>
                <span class="mr5">品牌 属性...</span>
                <span class="m-fr svg"><embed src="/static/img/jt.svg" type="image/svg+xml" /></span>
            </div>
        </div>
        <div class="goods-items  f-12">
            <div class="goods-comment">
              商品评价 ({{ $goods->comment_count }})
                <div class="m-fr c-ccc">查看全部<span class="svg"><embed src="/static/img/jt.svg" type="image/svg+xml" /></span></div>
            </div>
        </div>
        <div class="goods-items ">
            <div class="c-888 t-ac f-10 mb10">
              商品详情
            </div>
            <div class="goods-content f-14">
                {!! $goods->ext->content !!}
            </div>
        </div>
    </div>
</div>
<div id="skuAttrPop" class="weui-popup__container popup-bottom">
    <div class="weui-popup__overlay"></div>
    <div class="weui-popup__modal">
        <div class="m-popup m-popup-attr">
            <div class="weui-cells">
                <p class="t-ac mt10 mb15">产品参数</p>
                @foreach ($goods->ext->attr as $a)
                <div class="weui-cell mr10">
                    <div class="weui-cell__bd"><p>{{ $a }}</p></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div id="skuTextPop" class="weui-popup__container popup-bottom">
    <div class="weui-popup__overlay"></div>
    <div class="weui-popup__modal">
        <div class="m-popup m-popup-text">
            <div class="mt10 mr10 ml10 mb10">
                <div>
                    <div class="m-fl mr10"><img id="select-img" src="{{ $goods->ext->image_url[0]['thumb'] }}" width="60" height="60"></div>
                    <div class="goods-price">
                        <i class="price-symbol">¥</i>
                        <span class="price" id="select-price" data-price="{{ price_format($goods->shop_price) }}" data-oprice="{{ price_format($goods->shop_price) }}">{{ price_format($goods->shop_price) }}</span>
                    </div>
                    <div class="mb10">已选：<span id="color-select"></span><span id="select-attr"></span><span id="attr-select"></span></div>
                </div>
                @if ($goods->color)
                <div class="m-lineb m-cl pt10">
                    <div>颜色</div>
                    <div>
                    @foreach ($goods->color as $a)
                    <div class="goods-spec goods-color-items" id="{{ $a->goods_id }}-{{ $a->color_id }}" data-color="{{ $a->color }}" data-img="{{ $a->img }}">
                        @if ($a->color_img)
                        <img src="{{ $a->color_img }}">
                        @endif
                        {{ $a->color }}
                    </div>
                    @endforeach
                    </div>
                </div>
                @endif
                @if ($goods->attr)
                <div class="m-lineb m-cl pt10">
                    <div>规格</div>
                    @foreach ($goods->attr as $k=>$at)
                    <div class="{{ $a->goods_id }}-{{ $a->color_id }} @if($k!=0) m-hidden @else m-show @endif">
                    @foreach ($at as $k=>$a)
                    <div class="goods-spec goods-attr-items" data-id="{{ $a->attr_id }}" data-attr="{{ $a->attr }}" data-img="{{ $a->img }}" data-price="{{ price_format($a->attr_price) }}">
                        @if ($a->attr_img)
                        <img src="{{ $a->attr_img }}">
                        @endif
                        {{ $a->attr }}
                    </div>
                    @endforeach
                    </div>
                    @endforeach
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
                        <button id="select-buy" data-type="{{ $goods->type }}">立即购买</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js"></script>
<script>
    initDetail();
</script>
@endsection
