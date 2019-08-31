@extends('layouts.app')
@section('title', '评论')
@section('content')
<div class="container">
    @if (count($list) > 0)
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>商品评论</p>
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
                            <img src="{{ $g['tsrc'] }}" alt="">
                        </li>
                    @endforeach
                    </ul>
                </div>
                @endif
            </div>
            <div class="weui-cell__ft">{{ $l->date }}</div>
        </div>
    @endforeach
    </div>
    @endif
</div>
@endsection
@section('script')
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
        html += '</div><div class="weui-cell__ft">'+l.date+'</div></div>';
    });
    $('.weui-cells').append(html);
});
</script>
@endsection
