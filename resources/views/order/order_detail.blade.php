@extends('layouts.app')
@section('head')
<style>
    .weui-btn_mini{line-height: 24px;padding: 0 14px;}
    .weui-panel{background-color: #efeff4;}
    .weui-panel>div{background-color: white;}
    .weui-media-box__desc{line-height: normal;}
</style>
@endsection
@section('title', '订单详请')
@section('content')
<div class="container">
    <div class="weui-panel">
        <div class="weui-panel__bd">
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
        <div class="weui-panel__hd mt10">订单{{ $detail->order_sn }} <span class="m-fr">{{ date('Y-m-d H:i:s', $detail->add_time) }}</span></div>
        <div class="weui-panel__bd">
            @foreach ($detail->ordergoods as $k=>$g)
            <a href="javascript:void(0);" data-id="{{ $detail->order_id }}" class="weui-media-box weui-media-box_appmsg">
                <div class="weui-media-box__hd">
                    <img class="weui-media-box__thumb" src="{{ $g->img }}">
                </div>
                <div class="weui-media-box__bd">
                    <h4 class="weui-media-box__title f-14 m-name goods-name">{{ $g->goods_name }}</h4>
                    <p class="weui-media-box__desc">规格 {{ $g->spec_key }}</p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="weui-panel__ft">
            <a href="javascript:void(0);" class="weui-cell weui-cell_access weui-cell_link">
                <div class="weui-cell__bd">
                    @if($detail->pay_status == 1)
                            @if($detail->order_status == 1)
                            <bottom class="weui-btn weui-btn_mini weui-btn_warn m-fr order-quest" data-id="{{ $detail->order_id }}">确认收货</bottom>
                            @elseif($detail->order_status == 2)
                            <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr">已收货</bottom>
                            @elseif($detail->order_status === 0)
                            <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr">待发货</bottom>
                            @else
                            <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr">{{ $detail->statusstr }}</bottom>
                            @endif
                        @else
                        <bottom class="weui-btn weui-btn_mini weui-btn_warn m-fr order-pay" data-id="{{ $detail->order_id }}">待支付</bottom>
                        @endif
                </div>
            </a>    
        </div>

        <div class="weui-panel__bd mt10">
            <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
                <div class="weui-media-box__bd">
                    <p class="weui-media-box__desc">订单总价 <span class="m-fr">{{ price_format($detail->order_amount) }}</span></p>
                </div>
            </a>
        </div>
        
        <div class="weui-panel__bd mt10">
            <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
                <div class="weui-media-box__bd">
                    <h4 class="weui-media-box__title f-14 m-name goods-name">{{ $detail->province }} {{ $detail->city }} {{ $detail->district }} {{ $detail->address }}</h4>
                    <p class="weui-media-box__desc">收货人: {{ $detail->consignee }}&nbsp;&nbsp;&nbsp;&nbsp;手机: {{ $detail->mobile }}</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
   
</script>
@endsection
