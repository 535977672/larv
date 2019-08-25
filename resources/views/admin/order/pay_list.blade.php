@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">订单管理</a>
        <a>
            <cite>支付记录</cite>
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
                            <select name="type">
                                <option value="">支付方式</option>
                                <option value="1" @isset($requestes['type']) @if($requestes['type'] == 1) selected @endif @endisset>支付宝</option>
                                <option value="2" @isset($requestes['type']) @if($requestes['type'] == 2) selected @endif @endisset>微信</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="status">
                                <option value="">支付状态</option>
                                <option value="0" @isset($requestes['status']) @if($requestes['status'] === '0') selected @endif @endisset>未支付</option>
                                <option value="1" @isset($requestes['status']) @if($requestes['status'] == 1) selected @endif @endisset>手动验证过期</option>
                                <option value="2" @isset($requestes['status']) @if($requestes['status'] == 2) selected @endif @endisset>自动验证过期</option>
                                <option value="3" @isset($requestes['status']) @if($requestes['status'] == 3) selected @endif @endisset>回调支付成功</option>
                                <option value="4" @isset($requestes['status']) @if($requestes['status'] == 4) selected @endif @endisset>客户验证支付成功</option>
                                <option value="5" @isset($requestes['status']) @if($requestes['status'] == 5) selected @endif @endisset>手动验证支付成功</option>
                                <option value="6" @isset($requestes['status']) @if($requestes['status'] == 6) selected @endif @endisset>数据重复</option></select>
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
                                <th>订单</th>
                                <th>订单金额</th>
                                <th>支付方式</th>
                                <th>支付状态</th>
                                <th>创建时间</th>
                                <th>过期时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" data-id="{{ $l->order_id }}"></td>
                                <td><a class="c-red" href="/admin/order/orderlist?order_id={{ $l->o_id }}">{{ $l->o_id }}</a></td>
                                <td>{{ price_format($l->money) }}</td>
                                <td>{{ $l->type_str }}</td>
                                <td>{{ $l->status_str }}</td>
                                <td>{{ $l->create_time_str }}</td>
                                <td>{{ $l->expiring_str }}</td>
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