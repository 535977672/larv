@extends('layouts.app')
@section('head')
<link rel="stylesheet" type="text/css" href="/static/plugs/slideunlock/css/slideunlock.css">
@endsection
@section('title', '我的')
@section('content')
<div class="container me">
    <div><img src="/static/img/bg2.jpg" width="100%"></div>
    <div class="weui-grids">
        @auth
        <a href="javascript:;" class="weui-grid js_grid" id="logout">
            <div class="weui-grid__icon">
                <img src="/static/img/logout.png" alt="">
            </div>
            <p class="weui-grid__label">
                退出
            </p>
        </a>
        @endauth
        @guest
        <a href="javascript:;" class="weui-grid js_grid" id="login">
            <div class="weui-grid__icon">
                <img src="/static/img/login.png" alt="">
            </div>
            <p class="weui-grid__label">
                登录
            </p>
        </a>
        @endguest
        <a href="/order/list" class="weui-grid js_grid">
            <div class="weui-grid__icon">
                <img src="/static/img/order.png" alt="">
            </div>
            <p class="weui-grid__label">
                订单
            </p>
        </a>
        <a href="https://m.kuaidi100.com/app/query/?com=&nu=YT4053234734099&coname=meizu&callbackurl=http://test.test.com/test/kuaidi.html" class="weui-grid js_grid">
            <div class="weui-grid__icon">
                <img src="/static/img/login.png" alt="">
            </div>
            <p class="weui-grid__label">
                快递查询
            </p>
        </a>
        @auth
        @if($uid == 1)
        <a href="javascript:;" class="weui-grid js_grid" id="theme-chose">
            <div class="weui-grid__icon">
                <img src="/static/img/order.png" alt="">
            </div>
            <p class="weui-grid__label">
                主题
            </p>
        </a>
        @endif
        @endauth
    </div>
</div>
@auth
@if($uid == 1)        
<div id="themeAttrPop" class="weui-popup__container popup-bottom">
    <div class="weui-popup__overlay"></div>
    <div class="weui-popup__modal">
        <div class="m-popup m-popup-attr">
            <div class="weui-cells">
                <p class="t-ac mt10 mb15">主题</p>
                @foreach (theme() as $k=>$t)
                <div class="weui-cell mr10">
                    <div class="weui-cell__bd m-theme" style="background-color: {{ $t }};">{{ $k }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
@endauth
@endsection
@section('script')
<script type="text/javascript" src="/static/plugs/slideunlock/js/jquery.slideunlock.js"></script>
<script>
initMe();
</script>
@endsection
