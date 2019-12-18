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
                <div class="layui-card-body">
                    <div class="mt15">
                        <button class="layui-btn layui-btn-danger m-clean" data-url="/admin/cache/clean/99">
                            <i class="layui-icon"></i>所有数据缓存
                        </button>
                        <span class="ml5 c-gray">清除所有数据缓存</span>
                    </div>
                    <div class="mt15">
                        <button class="layui-btn layui-btn-danger m-clean" data-url="/admin/cache/clean/1">
                            <i class="layui-icon"></i>首页数据缓存
                        </button>
                        <span class="ml5 c-gray">清除首页类目数据缓存</span>
                    </div>
                    <div class="mt15">
                        <button class="layui-btn layui-btn-danger m-clean" data-url="/admin/cache/clean/2">
                            <i class="layui-icon"></i>搜索数据缓存
                        </button>
                        <span class="ml5 c-gray">清除商品列表搜索数据缓存</span>
                    </div>
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
            if(res.status !== 200){
                comm.msg(res.msg, 2);
            }else{
                comm.msg(res.msg,  1);
            }
        });
        return false;
    });
});
</script>
@endsection