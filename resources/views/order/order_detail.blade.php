@extends('layouts.app')
@section('title', '订单详请')
@section('content')
<div class="container order">
    <div class="weui-panel bgc-ec">
        <div class="weui-panel__bd mt5">
            <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
                <div class="weui-media-box__hd">
                    <img class="weui-media-box__thumb" style="width:32px;height:32px;vertical-align:middle;" src="/static/img/city.png">
                </div>
                <div class="weui-media-box__bd">
                    <h4 class="weui-media-box__title f-14 m-name goods-name">收货地址: {{ $detail->province }} {{ $detail->city }} {{ $detail->district }} {{ $detail->address }}</h4>
                    <p class="weui-media-box__desc">收货人: {{ $detail->consignee }}&nbsp;&nbsp;&nbsp;&nbsp;手机: {{ $detail->mobile }}</p>
                </div>
            </a>
        </div>
        <div>
            <div class="weui-panel__hd mt10">订单{{ $detail->order_sn }} <span class="m-fr">{{ date('Y-m-d H:i:s', $detail->add_time) }}</span></div>
            <div class="weui-panel__bd">
                @foreach ($detail->ordergoods as $k=>$g)
                <a href="javascript:void(0);" data-id="{{ $detail->order_id }}" class="weui-media-box weui-media-box_appmsg">
                    <div class="weui-media-box__hd">
                        <img class="weui-media-box__thumb" src="{{ $g->img }}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title f-14 m-name goods-name">{{ $g->goods_name }}</h4>
                        <p class="weui-media-box__desc">规格 {{ $g->spec_key }} x{{ $g->goods_num }}</p>
                        <div>
                            @if($detail->pay_status == 1)
                                @if($detail->order_status == 0 && $g->is_send == 0)
                                <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr">未发货</bottom>
                                @elseif($g->is_send == 1 && $g->is_receive == 0)
                                <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr order-quest" data-id="{{ $g->og_id }}">确认收货</bottom>
                                @elseif($g->is_receive == 1)
                                <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr">已收货</bottom>
                                @endif
                                @if($g->shipping_code)
                                <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr order-shipping" data-code="{{ $detail->shipping_code }}">查看物流</bottom>
                                @endif
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="weui-panel__ft">
                <a href="javascript:void(0);" class="weui-cell weui-cell_access weui-cell_link">
                    <div class="weui-cell__bd">
                        @if($detail->pay_status != 1)
                        <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr order-pay" data-id="{{ $detail->order_id }}" data-back="-1">待支付</bottom>
                        @endif
                        @if($detail->pay_status == 0 || $detail->order_status >= 2)
                        <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr order-del" data-id="{{ $detail->order_id }}">删除订单</bottom>
                        @endif
                    </div>
                </a>
            </div>
        </div>
        <div class="weui-panel__bd mt10">
            <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
                <div class="weui-media-box__bd">
                    <p class="weui-media-box__desc f-14">订单总价<span class="m-fr">￥{{ price_format($detail->total_amount) }}</span></p>
                    <p class="weui-media-box__desc f-14">网站优惠<span class="m-fr">-￥{{ price_format($detail->discount_money) }}</span></p>
                    <p class="weui-media-box__desc c-black mt5 f-16">实际支付<span class="m-fr c-red">￥{{ price_format($detail->order_amount) }}</span></p>
                </div>
            </a>
        </div>
        
        <div class="weui-panel__bd mt10">
            <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
                <div class="weui-media-box__bd">
                    <h4 class="weui-media-box__title f-14 t-ac">微信客服</h4>
                    <p class="weui-media-box__desc t-ac"><img style="width:240px;" src="/static/img/service.jpg"></p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
initOrder();
</script>
@endsection
