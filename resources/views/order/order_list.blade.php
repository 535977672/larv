@extends('layouts.app')
@section('head')
<style>
    .weui-btn_mini{line-height: 24px;padding: 0 14px;}
    .weui-panel{background-color: #efeff4;}
    .weui-panel__bd,.weui-panel__hd{background-color: white;}
    .weui-media-box__desc{line-height: normal;}
</style>
@endsection
@section('title', '订单列表')
@section('content')
<div class="container">
    <div class="weui-panel weui-panel_access">
        @if (count($list) > 0)
        @foreach ($list as $a)
        <div class="weui-panel__hd mt10">订单{{ $a->order_sn }} <span class="m-fr">{{ $a->add_time }}</span></div>
        <div class="weui-panel__bd">
            @foreach ($a->ordergoods as $g)
            <a href="javascript:void(0);" data-id="{{ $a->order_id }}" class="weui-media-box weui-media-box_appmsg">
                <div class="weui-media-box__hd">
                    <img class="weui-media-box__thumb" src="{{ $g->img }}">
                </div>
                <div class="weui-media-box__bd">
                    <h4 class="weui-media-box__title f-14 m-name">{{ $g->goods_name }}</h4>
                    <p class="weui-media-box__desc">规格 {{ $g->spec_key }}</p>
                    <div>
                        @if($a->pay_status == 1)
                            @if($a->order_status == 1)
                            <bottom class="weui-btn weui-btn_mini weui-btn_warn m-fr order-quest" data-id="{{ $a->order_id }}">确认收货</bottom>
                            @elseif($a->order_status == 2)
                            <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr">已收货</bottom>
                            @elseif($a->order_status === 0)
                            <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr">待发货</bottom>
                            @else
                            <bottom class="weui-btn weui-btn_mini weui-btn_default m-fr">{{ $a->statusstr }}</bottom>
                            @endif
                        @else
                        <bottom class="weui-btn weui-btn_mini weui-btn_warn m-fr order-pay" data-id="{{ $a->order_id }}">待支付</bottom>
                        @endif
                    </div>
                </div>
            </a>
             @endforeach
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection
@section('script')
<script>
@auth
loadData('/order/list', {}, function(goods){
    var html = '';
    $.each(goods, function(i, g){
        html += '<div class="weui-panel__hd mt10">订单'+g.order_sn+' <span class="m-fr">'+g.add_time+'</span></div>'
                    +'<div class="weui-panel__bd">';
            $.each(g.ordergoods, function(j, gg){
                html += '<a href="javascript:void(0);" data-id="'+g.order_id+'" class="weui-media-box weui-media-box_appmsg">'
                            +'<div class="weui-media-box__hd">'
                                +'<img class="weui-media-box__thumb" src="'+gg.img+'">'
                            +'</div>'
                            +'<div class="weui-media-box__bd">'
                                +'<h4 class="weui-media-box__title f-14 m-name">'+gg.goods_name+'</h4>'
                                +'<p class="weui-media-box__desc">规格 '+gg.spec_key+'</p>'
                                +'<div>'
                                    +'<bottom class="weui-btn weui-btn_mini weui-btn_default m-fr order-detail" data-id="'+g.order_id+'">详情</bottom>'
                                +'</div>'
                            +'</div>'
                        +'</a>';
            });
           html += '</div>'; 
    });
    $('.weui-panel').append(html);
    initOrder();
}, 'GET', 0, 0);
@endauth
@guest
initOrderList();
@endguest
initOrder();
</script>
@endsection