@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a>
            <cite>数据转移</cite>
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
                    <button class="layui-btn layui-btn-danger delAll" data-url="g/cd">
                        <i class="layui-icon"></i>批量删除
                    </button>
                    <button class="layui-btn layui-btn-normal mulYes">
                        <i class="layui-icon layui-icon-template-1"></i>批量确认
                    </button>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="" lay-skin="primary" id="checkboxall"></th>
                                <th>名称</th>
                                <th>源地址</th>
                                <th>图片</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" data-id="{{ $l->id }}"></td>
                                <td><a class="c-red" href="/admin/g/cd/{{ $l->id }}">{{ $l->title }}</a></td>
                                <td><a class="c-red" target="_blank" href="{{ $l->url }}">{{ $l->url }}</a></td>
                                <td><img src="{{ $l->cover[0]->thumb }}" alt=""></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <div>
                            {{ $list->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
layui.use(['comm', 'form', 'jquery'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,$ = layui.jquery;
    comm.checkbox();
    $('.mulYes').on('click', function () {
        var ids = comm.checkIds();
        if(!ids) return;
        comm.confirm('批量确认', function(){
            comm.ajax('/admin/g/mulcheck', {ids: ids}, function(res){
                if(res.status !== 200){
                    comm.msg(res.msg, 2);
                }else{
                    if(res.data) {
                        comm.msg('有部分错误信息', 2);
                        console.log(res.data);
                    }else{
                        comm.msg('操作成功', 1);
                        comm.winReload();
                    }
                }
            });
            return false;
        });
    });
});
</script>
@endsection