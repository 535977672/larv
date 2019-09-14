@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">订单管理</a>
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
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="开始日" name="start" id="start"  @isset($requestes['start']) value="{{ $requestes['start'] }}" @endisset></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="截止日" name="end" id="end"  @isset($requestes['end']) value="{{ $requestes['end'] }}" @endisset></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="paytype">
                                <option value="">支付方式</option>
                                <option value="1" @isset($requestes['paytype']) @if($requestes['paytype'] == 1) selected @endif @endisset>支付宝</option>
                                <option value="2" @isset($requestes['paytype']) @if($requestes['paytype'] == 2) selected @endif @endisset>微信</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="is_send">
                                <option value="">发货</option>
                                <option value="0" @isset($requestes['is_send']) @if($requestes['is_send'] === '0') selected @endif @endisset>未发货</option>
                                <option value="1" @isset($requestes['is_send']) @if($requestes['is_send'] == 1) selected @endif @endisset>已发货</option>
                                <option value="2" @isset($requestes['is_send']) @if($requestes['is_send'] == 2) selected @endif @endisset>已换货</option>
                                <option value="3" @isset($requestes['is_send']) @if($requestes['is_send'] == 3) selected @endif @endisset>已退货</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="is_buy">
                                <option value="">购买</option>
                                <option value="0" @isset($requestes['is_buy']) @if($requestes['is_buy'] === '0') selected @endif @endisset>未购买</option>
                                <option value="1" @isset($requestes['is_buy']) @if($requestes['is_buy'] == 1) selected @endif @endisset>已购买</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="order_status">
                                <option value="">订单状态</option>
                                <option value="0" @isset($requestes['order_status']) @if($requestes['order_status'] === '0') selected @endif @endisset>待确认</option>
                                <option value="1" @isset($requestes['order_status']) @if($requestes['order_status'] == 1) selected @endif @endisset>已确认</option>
                                <option value="2" @isset($requestes['order_status']) @if($requestes['order_status'] == 2) selected @endif @endisset>已收货</option>
                                <option value="3" @isset($requestes['order_status']) @if($requestes['order_status'] == 3) selected @endif @endisset>已取消</option>
                                <option value="4" @isset($requestes['order_status']) @if($requestes['order_status'] == 4) selected @endif @endisset>已完成</option>
                                <option value="5" @isset($requestes['order_status']) @if($requestes['order_status'] == 5) selected @endif @endisset>已作废</option></select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="pay_status">
                                <option value="">支付状态</option>
                                <option value="0" @isset($requestes['pay_status']) @if($requestes['pay_status'] === '0') selected @endif @endisset>未支付</option>
                                <option value="1" @isset($requestes['pay_status']) @if($requestes['pay_status'] == 1) selected @endif @endisset>已支付</option>
                                <option value="3" @isset($requestes['pay_status']) @if($requestes['pay_status'] == 3) selected @endif @endisset>已退款</option>
                                <option value="4" @isset($requestes['pay_status']) @if($requestes['pay_status'] == 4) selected @endif @endisset>拒绝退款</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="goods_type">
                                <option value="">商品类型</option>
                                <option value="1" @isset($requestes['goods_type']) @if($requestes['goods_type'] == 1) selected @endif @endisset>普通商品</option>
                                <option value="2" @isset($requestes['goods_type']) @if($requestes['goods_type'] == 2) selected @endif @endisset>套餐商品</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="is_send">
                                <option value="">是否发货</option>
                                <option value="0" @isset($requestes['is_send']) @if($requestes['is_send'] === '0') selected @endif @endisset>未发货</option>
                                <option value="1" @isset($requestes['is_send']) @if($requestes['is_send'] == 1) selected @endif @endisset>已发货</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="order_sn" placeholder="请输入订单号" autocomplete="off" class="layui-input" @isset($requestes['order_sn']) value="{{ $requestes['order_sn'] }}" @endisset>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
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
                                <th>订单编号</th>
                                <th>商品</th>
                                <th>收货人</th>
                                <th>订单/应付金额</th>
                                <th>状态</th>
                                <th>支付方式</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" data-id="{{ $l->order_id }}"></td>
                                <td><a class="c-red" href="/admin/order/orderlist?order_id={{ $l->order_id }}">{{ $l->goods_sn }}</a></td>
                                <td><a class="c-red" target="_blank" href="{{ $l->o_url }}">{{ $l->goods_name }}</a></td>
                                <td>{{ $l->consignee }}</td>
                                <td style="width: 120px;">订单金额：￥{{ price_format($l->total_amount) }}<br/>应付金额：￥{{ price_format($l->order_amount) }}</td>
                                <td style="width: 110px;">订单状态： {{ $l->order_status_str }}<br/>发货状态： {{ $l->is_send_str }}<br/>支付状态： {{ $l->pay_status_str }}</td>
                                <td>{{ $l->paytype_str }}</td>
                                <td class="td-manage">
                                    @if($l->is_send == 0)
                                    <a title="确认已发货" class="ordergoodssend" data-url="/admin/order/ordergoodssend/{{ $l->og_id }}" href="javascript:;">
                                        <i class="layui-icon">&#xe698;</i>已发货</a>
                                    @endif
                                    @if($l->is_send == 1)
                                    <a title="添加物流信息" class="ordergoodsship" data-url="/admin/order/ordergoodsship/{{ $l->og_id }}" href="javascript:;">
                                        <i class="layui-icon">&#xe7ae;</i>物流</a>
                                    @endif
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
layui.use(['comm', 'form', 'layer','laydate', 'jquery'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,laydate = layui.laydate
    ,$ = layui.jquery;
    
    //执行一个laydate实例
    laydate.render({
        elem: '#start' //指定元素
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#end' //指定元素
    });
    
    $('.ordergoodssend').on('click', function(){
        var o = $(this);
        comm.confirm('确认已发货', function(){
            comm.ajax(o.attr('data-url'), {}, function(res){
                if(res.status === 200){
                    location.reload();
                }else{
                    layer.msg(res.msg, {icon: 2});
                }
            });
        });
    });
    
    $('.ordergoodsship').on('click', function(){
        var o = $(this);
        var html = '<div class="mt10 mb10 mr10 ml10"><form class="layui-form" method="get" name="shipping">'
                            +'<input class="layui-input mt10" placeholder="物流" name="shipping_name"  id="shipping_name">'
                            +'<input class="layui-input mt10" placeholder="物流单号" name="shipping_code"  id="shipping_code">'
                        +'<div class="layui-input-inline layui-show-xs-block t-ac mt20">'
                            +'<button class="layui-btn" id="submit">提交</button>'
                        +'</div>'
                    +'</form></div>';
        var index = layer.open({
            type: 1,
            title: '设置物流',
            area: ['300px', '200px'],
            content: html
        });
        form.render('select');
        $('#submit').on('click', function(){
            comm.ajax(o.attr('data-url'), {
                shipping_code: $('#shipping_code').val(),
                shipping_name: $('#shipping_name').val()
            }, function(res){
                if(res.status === 200){
                    location.reload();
                }else{
                    layer.msg(res.msg, {icon: 2});
                }
            });
        });
    });
});
</script>
@endsection