@extends('layouts.admin')
@section('class', 'index')
@section('content')
<!-- 顶部开始 -->
<div class="container">
    <div class="logo">
        <a href="/admin">Admin</a></div>
    <div class="left_open">
        <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
    </div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">{{ $username }}</a>
            <dl class="layui-nav-child">
                <!-- 二级菜单 -->
                <dd>
                    <a href="javascript:void(0);" id="logout">退出</a>
                </dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index">
            <a href="/admin">前台首页</a></li>
    </ul>
</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="商品管理">&#xe6b8;</i>
                    <cite>商品管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                    <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('数据转移','/admin/g/c')">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>数据转移</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="订单管理">&#xe723;</i>
                    <cite>订单管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                    <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('订单列表','/admin/order/orderlist')">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单列表</cite>
                        </a>
                        <a onclick="xadmin.add_tab('商品列表','/admin/order/ordergoodslist')">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>商品列表</cite>
                        </a>
                        <a onclick="xadmin.add_tab('支付记录','/admin/order/paylist')">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>支付记录</cite>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home">
                <i class="layui-icon">&#xe68e;</i>我的桌面</li></ul>
        <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
            <dl>
                <dd data-type="this">关闭当前</dd>
                <dd data-type="other">关闭其它</dd>
                <dd data-type="all">关闭全部</dd></dl>
        </div>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='/admin/main' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>
<div class="page-content-bg"></div>
<style id="theme_style"></style>
@endsection

@section('script')
<script>
layui.use(['jquery', 'comm'], function($, comm){
    $('#logout').on('click', function(){
        comm.logout();
    });
});
</script>
@endsection