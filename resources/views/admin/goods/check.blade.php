@extends('layouts.admin')
@section('head')
<link rel="stylesheet" href="/static/admin/css/login.css">
@endsection
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
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="" lay-skin="primary"></th>
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
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
layui.use(['comm', 'form'], function(){
    var form = layui.form
    ,comm = layui.comm;
});
</script>
@endsection