@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a>
            <cite>添加套餐</cite>
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
                    <form class="layui-form layui-col-space5">
                        <input class="layui-input" type="hidden" name="is_recommend" value="1">
                        <input class="layui-input" type="hidden" name="is_new" value="1">
                        <input class="layui-input" type="hidden" name="is_hot" value="1">
                        <input class="layui-input" type="hidden" name="type" value="2">
                        <input class="layui-input" type="hidden" name="goods_type" value="0">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="商品名" name="goods_name">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="库存" name="store_count" value="10">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="价格" name="shop_price" id="shop_price" value="0">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="成本" name="cost_price" id="cost_price" value="0">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="图片" name="original_img">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <a href="javascript:;" class="layui-btn" lay-submit="" lay-filter="add" data-url="/admin/g/goodsteamadd">
                                <i class="layui-icon"></i>添加</a>
                        </div>
                    </form>
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
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" data-id="{{ $l->attr_id }}" data-gid="{{ $l->goods_id }}" data-p1="{{ $l->attr_price }}" data-p2="{{ $l->realprice }}"></td>
                                <td>{{ $l->goods->goods_name }}</td>
                                <td>
                                    @if($l->img)
                                    <a class="c-red" target="_blank" href="{{ $l->img }}"><img src="{{ $l->img }}" height="50px" width="50px"></a>
                                    @elseif($l->color_id > 0 && $l->color->img)
                                    <a class="c-red" target="_blank" href="{{ $l->color->img }}"><img src="{{ $l->color->img }}" height="50px" width="50px"></a>
                                    @else
                                    <a class="c-red" target="_blank" href="{{ $l->goods->original_img }}"><img src="{{ $l->goods->original_img }}" height="50px" width="50px"></a>
                                    @endif
                                </td>
                                <td>属性价{{ price_format($l->attr_price) }}<br/>成本价{{ price_format($l->realprice) }}<br/>差价{{ price_format(bcsub($l->attr_price,$l->realprice)) }}</td>
                                
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
    
    var d;
    
    $("tbody .layui-form-checkbox").on('click', function(e){
        var data = $("tbody .layui-form-checked").not('.header').prev('input')
            ,shop_price=0,cost_price=0;
        d = [];
        $.each(data, function(i,v){
            var o = $(v);
            if(d[o.attr('data-gid')]) {
                layer.msg('重复数据', {icon: 2});
            }else{
                d[o.attr('data-gid')] = o.attr('data-gid')+'-'+o.attr('data-id');
                shop_price = shop_price + parseInt(o.attr('data-p1'));
                cost_price = cost_price + parseInt(o.attr('data-p2'));
            }
        });
        $('#shop_price').val(shop_price);
        $('#cost_price').val(cost_price);
    });
    
    //监听提交
    form.on('submit(add)', function(data){
        data.field.ids = d;
        comm.ajax(data.elem.dataset.url, data.field, function(res){
            if(res.status !== 200){
                layer.msg(res.msg, {icon: 2});
            }else{
                window.location.reload();
            }
        });
        return false;
    });
   
    
});
</script>
@endsection