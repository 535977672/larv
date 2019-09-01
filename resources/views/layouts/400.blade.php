@extends('layouts.app')
@section('title', '消息提示')
@section('content')
<div class="weui-msg">
    <div class="weui-msg__icon-area"><i class="weui-icon-info weui-icon_msg"></i></div>
    <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">操作失败</h2>
        <p class="weui-msg__desc">{{ $msg }}</p>
    </div>
    <div class="weui-msg__opr-area">
        <p class="weui-btn-area">
            <a href="javascript:history.go(-1);" class="weui-btn weui-btn_primary">确认</a>
            <a href="javascript:history.go(-1);" class="weui-btn weui-btn_default">返回</a>
        </p>
    </div>
    <div class="weui-msg__extra-area">
        <div class="weui-footer">
            <p class="weui-footer__text">优甜缘</p>
        </div>
    </div>
</div>
@endsection