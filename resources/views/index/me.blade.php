@extends('layouts.app')
@section('title', 'index')
@section('content')
<div class="layui-container">
    <div class="weui-search-bar" id="searchBar">
        <form class="weui-search-bar__form" method="get" action="search">
            <div class="weui-search-bar__box">
                <i class="weui-icon-search"></i>
                <input type="search" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required="">
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
        开发中...{{ route('login') }}  {{ route('register') }}   {{ route('password.request') }} {{ route('password.email') }} {{ route('logout') }}
    </div>
    <a id="keep" href="https://m.kuaidi100.com/app/query/?com=&nu=YT4053234734099&coname=meizu&callbackurl=http://test.test.com/test/kuaidi.html">快递查询</a>
    <div id="logout">
        退出
    </div>
    <div id="login">
        登录
    </div>
    
    <div>
        <a href="/order/list">我的订单</a>
    </div>
</div>
@endsection
@section('script')
<script>
    $('#logout').on('click', function(){
        logout();
    });
    $('#login').on('click', function(){
        login();
    });
</script>
@endsection
