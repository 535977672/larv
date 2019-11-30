@extends('layouts.app')
@section('title', '购物车列表')
@section('content')
<div class="container cart">
    <div class="cartlist"></div>
    <button id="cart-buy" data-clock="0"><span class="mr10" id="money">¥0.00</span> 提交订单</button>
</div>
@endsection
@section('script')
<script>
initCartList(2);
</script>
@endsection
