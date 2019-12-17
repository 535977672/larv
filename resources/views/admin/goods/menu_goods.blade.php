@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a href="javascript:void(0);">商品类目</a>
        <a>
            <cite>类目商品</cite>
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
                    <button class="layui-btn" onclick="parent.xadmin.add_tab('商品列表','/admin/g/goodslist')">
                        <i class="layui-icon"></i>添加商品
                    </button>
                    <button class="layui-btn layui-btn-danger delAll" data-url="g/menugoodsdel">
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
                                <th>ID</th>
                                <th>商品</th>
                                <th>图片</th>
                                <th>价格</th>
                                <th>类目</th>
                                <th>性别</th>
                                <th>分类</th>
                                <th>链接类型</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" id="checkboxall" data-id="{{ $l->id }}"></td>
                                <td>{{ $l->goods_id	 }}</td>
                                <td>
                                    <a class="c-red" target="_blank" href="/goods/detail/{{ $l->goods_id }}">{{ $l->goods_name }}</a>
                                </td>
                                <td><a class="c-red" target="_blank" href="{{ $l->original_img }}"><img lay-src="{{ $l->original_img }}" height="100px" width="100px"></a></td>
                                <td>{{ price_format($l->shop_price) }}</td>
                                <td>{{ $l->menu->name }}</td>
                                <td>@if($l->sex=='0')不限@elseif($l->sex==1)男装@elseif($l->sex==2)女装@elseif($l->sex==3)童装@elseif($l->sex==4)日韩女装@elseif($l->sex==5)男鞋@elseif($l->sex==6)女鞋@endif</td>
                                <td>@if($l->cate){{ $l->cate->name }}@endif</td>
                                <td>@if($l->single=='0')单品@elseif($l->single==1)分类@endif</td>
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
layui.use(['comm', 'form', 'layer', 'jquery','flow'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,flow = layui.flow
    ,$ = layui.jquery;
    comm.checkbox();
    flow.lazyimg({scrollElem: '.table-over'});
});
</script>
@endsection