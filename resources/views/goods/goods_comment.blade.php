@extends('layouts.app')
@section('title', '评价')
@section('content')
<div class="container">
    @if (count($list) > 0)
    <div id="m-top"></div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p class="t-ac">商品评价</p>
        </div>
    </div>
    <div class="weui-cells mt2">
    @foreach ($list as $l)
        <div class="weui-cell f-14">
            <div class="weui-cell__bd">
                <p class="m-fulltxt">{{ $l->content }}</p>
                @if(count($l->img))
                <div class="m-photos">
                    <ul class="m-photos-thumb">
                    @foreach ($l->img as $g)
                        <li data-src="{{ $g['osrc'] }}">
                            <img src="{{ $g['osrc'] }}" alt="">
                        </li>
                    @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    @endforeach
    </div>
    @else
    <div class="weui-msg">
        <div class="weui-msg__text-area mt100">
            <img src="/static/img/empty.png" alt="没有数据">
            <p class="weui-msg__desc">没有数据</p>
        </div>
    </div>
    @endif
</div>
@endsection
@section('script')
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js"></script>
<script>
loadData('/goods/comment/{{ $id }}', {}, function(list){
    var html = '';
    $.each(list, function(i, l){
        html += '<div class="weui-cell f-14">'
                    +'<div class="weui-cell__bd">'
                        +'<p class="m-fulltxt">'+l.content+'</p>';
        if(l.img.length>0){
            html += '<div class="m-photos">'
                        +'<ul class="m-photos-thumb">';
            $.each(l.img, function(j, m){
                html += '<li data-src="'+m.osrc+'">'
                            +'<img src="'+m.tsrc+'" alt="">'
                        '</ul>';
            });
            html += '</div>';
        }
        html += '</div></div>';
    });
    $('.weui-cells').append(html);
    privewImgComment();
});
privewImgComment();
</script>
@endsection
