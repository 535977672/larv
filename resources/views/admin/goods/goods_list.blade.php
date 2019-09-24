@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a>
            <cite>商品列表</cite>
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
                    <button class="layui-btn" onclick="xadmin.open('添加套餐','/admin/g/goodsteamtoadd', '1000', '520')">
                        <i class="layui-icon"></i>添加套餐
                    </button>
                    <button class="layui-btn isonsale" data-status="1" data-url="g/isonsale">上架</button>
                    <button class="layui-btn isonsale" data-status="0" data-url="g/isonsale">下架</button>
                </div>
                <div class="layui-card-body table-over">
                    <table class="layui-table layui-form">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="" lay-skin="primary">
                                </th>
                                <th>商品</th>
                                <th>图片</th>
                                <th>价格</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" data-id="{{ $l->goods_id	 }}"></td>
                                <td>
                                    @if($l->type == 1)
                                    <a class="c-red" target="_blank" href="{{ $l->ext->original_url }}">{{ $l->goods_name }}</a>
                                    @else
                                    {{ $l->goods_name }}
                                    @endif
                                </td>
                                <td><a class="c-red" target="_blank" href="{{ $l->original_img }}"><img src="{{ $l->original_img }}" height="100px" width="100px"></a></td>
                                <td>本店价{{ price_format($l->shop_price) }}<br/>成本价{{ price_format($l->cost_price) }}</td>
                                <td>{{ $l->is_on_sale_str }}</td>
                                <td class="td-manage">
                                </td>
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
layui.use(['comm', 'form', 'layer', 'jquery'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,$ = layui.jquery;
    $('.isonsale').on('click', function(){
        isonsale($(this);
    });
    function isonsale(obj){
        var data = $("tbody .layui-form-checked").not('.header').prev('input')
        ,ids = '';
        $.each(data, function(i,v){
            ids = ids + ',' +$(v).attr('data-id');
        });
        ids = ids.substr(1);
        if(comm.isEmpty(ids)) {
            layer.msg('选择数据', {icon: 2});
            return;
        }
        layer.confirm('确认操作？',function(index){
            comm.ajax('/admin/'+obj.attr('data-url'), {ids: ids, status: obj.attr('data-status')}, function(res){
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