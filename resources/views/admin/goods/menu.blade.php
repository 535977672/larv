@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a>
            <cite>商品类目</cite>
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
                    <button class="layui-btn" onclick="xadmin.open('添加类目','/admin/g/menutoadd', '600', '600')">
                        <i class="layui-icon"></i>添加类目
                    </button>
                    <button class="layui-btn layui-btn-danger delAll" data-url="g/menudel">
                        <i class="layui-icon"></i>批量删除
                    </button>
                </div>
                <div class="layui-card-body table-over">
                    <table class="layui-table layui-form">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="" lay-skin="primary">
                                </th>
                                <th>类目</th>
                                <th>排序</th>
                                <th>背景</th>
                                <th>类型</th>
                                <th>数量</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" id="checkboxall" data-id="{{ $l->menu_id }}"></td>
                                <td><a href="javascript:;" style="color: red;" onclick="parent.xadmin.add_tab('{{ $l->name }}-商品','/admin/g/menugoods/{{ $l->menu_id }}')">{{ $l->name }}</a></td>
                                <td>{{ $l->sort }}</td>
                                <td>@if($l->bg)<div style="margin: 5px; text-align: center; background-color: {{ $l->bg }}">{{ $l->bg }}</div>@endif</td>
                                <td>{{ $l->type }}</td>
                                <td>{{ $l->limit }}</td>
                                <td class="td-manage">
                                </td>
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
layui.use(['comm', 'form', 'layer', 'jquery'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,$ = layui.jquery;
    comm.checkbox();
});
</script>
@endsection