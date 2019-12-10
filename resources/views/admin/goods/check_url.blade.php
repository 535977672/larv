@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a>
            <cite>数据过期检查</cite>
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
                    <form class="layui-form layui-col-space5" method="get">
                        <div class="layui-input-block layui-show-xs-block">
                            <div>{{ $list }}</div>
                        </div>
                        <div class="layui-input-block layui-show-xs-block">
                            <textarea class="layui-textarea" placeholder="下架ID"  name="ids" id="ids"></textarea>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger isonsale" data-status="0" data-url="g/isonsale">下架</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
layui.use(['comm', 'form', 'jquery','layer'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,$ = layui.jquery;

    $('.isonsale').on('click', function(){
        isonsale($(this));
    });
    function isonsale(obj){
        var ids = $('#ids').val();
        if(comm.isEmpty(ids)) {
            layer.msg('选择数据', {icon: 2});
            return;
        }
        layer.confirm('确认操作？',function(index){
            comm.ajax('/admin/'+obj.attr('data-url'), {ids: ids, status: 0}, function(res){
                if(res.status !== 200){
                    layer.msg(res.msg, {icon: 2});
                }else{
                    layer.msg('操作成功', {icon: 1});
                    location.reload();
                }
            });
            return false;
        });
    }
});
</script>
@endsection