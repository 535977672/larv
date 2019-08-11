@extends('layouts.admin')
@section('head')
<link rel="stylesheet" href="/static/admin/css/login.css">
@endsection
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">订单管理</a>
        <a>
            <cite>订单列表</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                </div>    
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
layui.use(['comm', 'form', 'layer','laydate'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,laydate = layui.laydate;
    
  
});
</script>
@endsection