@extends('layouts.app')
@section('title', '订单确认')
@section('content')
<div class="container">
    <div class="weui-panel weui-panel_access">
        <div class="weui-panel__hd">订单确认</div>
        <div class="weui-panel__bd">
            <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
                <div class="weui-media-box__hd">
                    <img class="weui-media-box__thumb" src="{{ $param['img'] }}">
                </div>
                <div class="weui-media-box__bd">
                    <h4 class="weui-media-box__title f-14 m-name">{{ $param['goods_name'] }}</h4>
                    <p class="weui-media-box__desc">规格{{ $param['colorname'] }} {{ $param['attr'] }} 数量x{{ $param['num'] }}</p>
                </div>
            </a>
        </div>
    </div>
    @if($param['type'] == 2 && count($param['sub']))
    <div class="weui-panel weui-panel_access">
        <div class="weui-panel__hd">套餐详情</div>
        <div class="weui-panel__bd">
            @foreach ($param['sub'] as $k=>$a)
            <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
                <div class="weui-media-box__hd">
                    <img class="weui-media-box__thumb" src="{{ $a['img'] }}">
                </div>
                <div class="weui-media-box__bd">
                    <h4 class="weui-media-box__title f-14 m-name">{{ $a['goods_name'] }}</h4>
                    <p class="weui-media-box__desc">规格{{ $a['colorname'] }} {{ $a['attr'] }}</p>
                </div>
            </a>
             @endforeach
        </div>
    </div>
    @endif
    <form id="myform">
    <div class="weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <input class="weui-input blurs" name="consignee" type="text" placeholder="收货人姓名" value="{{ $param['uaddr']['consignee'] }}">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <input class="weui-input blurs" name="mobile" type="text" placeholder="手机号码" value="{{ $param['uaddr']['mobile'] }}">
            </div>
        </div>
        <div class="weui-cell weui-cell_select weui-cell_select-after" style="padding: 10px 15px;">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">省市区</label>
            </div>
            <div class="weui-cell__bd">
                <input type="text" name="uaddr" class="weui-input f12" id='city-picker' value="@if($param['uaddr']['province']){{ $param['uaddr']['address'] }} {{ $param['uaddr']['city'] }} {{ $param['uaddr']['district'] }}@endif"/>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <input class="weui-input blurs" name="address" type="text" placeholder="详细地址" value="{{ $param['uaddr']['address'] }}">
            </div>
        </div>
    </div>
    <input name="price" type="hidden" value="{{ $param['order_amount'] }}">
    <input name="randstr" type="hidden" value="{{ $param['randstr'] }}">
    <input name="datakey" type="hidden" value="{{ $param['datakey'] }}">
    </form>
    <button id="order-buy" data-clock="0"><span class="mr10" id="money">¥{{ price_format($param['order_amount']) }}</span> 提交订单</button>
</div>
@endsection
@section('script')
<script src="/js/city-picker.min.js"></script>
<script>
    initRequestes();
</script>
@endsection
