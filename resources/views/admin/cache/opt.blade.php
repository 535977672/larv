@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">设置</a>
        <a>
            <cite>缓存清理</cite>
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
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger m-clean" data-url="/admin/cache/clean/1">
                        <i class="layui-icon"></i>清理数据缓存
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
layui.use(['comm', 'jquery'], function(){
    var comm = layui.comm
    ,$ = layui.jquery;
    $('.m-clean').on('click', function () {
        comm.ajax($(this).attr('data-url'), {}, function(res){
            comm.msg(res.msg, 2);
        });
        return false;
    });
});
</script>
@endsection