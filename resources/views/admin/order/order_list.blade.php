@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">订单管理</a>
        <a>
            <cite>订单列表</cite>
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
                            <select name="type">
                                <option value="">商品类型</option>
                                <option value="1" @isset($requestes['type']) @if($requestes['type'] == 1) selected @endif @endisset>普通商品</option>
                                <option value="2" @isset($requestes['type']) @if($requestes['type'] == 2) selected @endif @endisset>套餐商品</option>
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
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger delAll" data-url="order/orderdel">
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
                                <th>订单编号</th>
                                <th>收货人</th>
                                <th>订单金额</th>
                                <th>应付金额</th>
                                <th>订单状态</th>
                                <th>支付状态</th>
                                <th>发货状态</th>
                                <th>支付方式</th>
                                <th>配送方式</th>
                                <th>下单时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" data-id="{{ $l->order_id }}"></td>
                                <td>{{ $l->order_sn }}</td>
                                <td>{{ $l->consignee }}</td>
                                <td>{{ price_format($l->total_amount) }}</td>
                                <td>{{ price_format($l->order_amount) }}</td>
                                <td>{{ $l->order_status_str }}</td>
                                <td>{{ $l->pay_status_str }}</td>
                                <td>0</td>
                                <td>{{ $l->paytype_str }}</td>
                                <td>0</td>
                                <td>0</td>
                                <td class="td-manage">
                                    <a title="查看" onclick="xadmin.open('订单详情','/admin/order/detail/{{ $l->order_id }}')" href="javascript:;">
                                        <i class="layui-icon">&#xe63c;</i></a>
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
layui.use(['comm', 'form', 'layer','laydate'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,laydate = layui.laydate;
    
    //执行一个laydate实例
    laydate.render({
        elem: '#start' //指定元素
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#end' //指定元素
    });
});
</script>
@endsection