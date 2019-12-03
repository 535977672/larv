@extends('layouts.app')
@section('title', '分类')
@section('content')
<div class="container">
    <div class="m-cate-base mt5 p5 weui-flex t-ac">
        <div class="weui-flex__item" data-sex="0"><div class="c-cate-b c-cate-s">全部</div></div>
        <div class="weui-flex__item" data-sex="1"><div class="c-cate-b">男装</div></div>
        <div class="weui-flex__item" data-sex="2"><div class="c-cate-b">女装</div></div>
        <div class="weui-flex__item" data-sex="3"><div class="c-cate-b">儿童</div></div>
    </div>
    <div class="m-cate mt5 p5">
        @if (count($list) > 0)
        @foreach ($list as $g)
        <a href="/goods/search?cid={{ $g->id }}">{{ $g->name }}</a>
        @endforeach
        @endif
    </div>
</div>
@endsection
@section('script')
<script>
    cate();
</script>
@endsection
