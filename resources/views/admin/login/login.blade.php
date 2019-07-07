@extends('layouts.admin')
@section('class', 'login-bg')
@section('head')
<link rel="stylesheet" href="/static/admin/css/login.css">
@endsection
@section('content')
<div class="login layui-anim layui-anim-up">
    <div class="message">AMIEF登录</div>
    <div id="darkbannerwrap"></div>

    <form method="post" class="layui-form" >
        <input name="name" placeholder="用户名"  type="text" lay-verify="required" autocomplete="off" class="layui-input" >
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="button">
        <hr class="hr20" >
    </form>
</div>
@endsection
@section('script')
<script>
layui.use(['comm', 'form'], function(){
    var form = layui.form
    ,comm = layui.comm;
    
    //监听提交
    form.on('submit(login)', function(data){
        comm.login(data.field);
    });
});
</script>
@endsection