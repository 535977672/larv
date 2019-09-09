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
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/static/plugs/slideunlock/js/jquery.slideunlock.js"></script>
<script>
initMe();
</script>
@endsection
