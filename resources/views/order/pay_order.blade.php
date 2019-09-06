@extends('layouts.app')
@section('title', '订单支付')
@section('head')
<style>
    body{background: #f7f7f7;}
</style>
@endsection
@section('content')
<div class="container">
    <h1 class="mod-title">
        <span class="ico_log @if($type == 1) ico-1 @else ico-2 @endif"></span>
    </h1>
    <div class="mod-ct">
        <div class="order"></div>
        <div class="amount" id="money" data-id="{{ $oid }}" data-exp="{{ $exp }}"></div>
        <div class="qrcode-img-wrapper" data-role="qrPayImgWrapper">
            <div data-role="qrPayImg" class="qrcode-img-area">
                <div class="ui-loading qrcode-loading" data-role="qrPayImgLoading"></div>
                <div style="position: relative;display: inline-block;">
                    <img id="show_qrcode" width="300" src="{{ $qrcode }}" title="" style="display: block;">
                </div>
            </div>
        </div>             
        <div class="time-item">      
            <div class="time-item" id="msg" style="color:red"><h1>1.请在订单有效期支付<br>2.请勿重复支付，一律不到账<br>3.请输入正确的金额，否则一律不到账</h1></div>
            <div class="time-item"><h1>订单号:{{ $code }}</h1> </div>
            <strong id="hour_show"><s id="h"></s>0时</strong>
            <strong id="minute_show"><s></s>00分</strong>
            <strong id="second_show"><s></s>00秒</strong>
        </div>
        <div class="tip">
            <div class="ico-scan"></div>
            <div class="tip-text">
                    <p id="showtext">打开@if($type == 1)支付宝 @else微信 @endif [扫一扫]</p>
            </div>
        </div>
        <div class="tip-text">
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    initPay();
</script>
@endsection
