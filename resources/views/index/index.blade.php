<!DOCTYPE>
<html lang="zh-CN">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>优甜缘</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css">
    <link rel="stylesheet" href="/static/layer_mobile/need/layer.css">
    <link rel="stylesheet" href="/static/css/main.css?time={{ time() }}">
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
        <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
        <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .weui-tab iframe{width: 100%; height: 100%; border: none;}
    </style>
</head>

<body ontouchstart class="scroll">
    <div class="weui-tab">
      <div class="weui-tab__bd">
        <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active" data-url="/index/main">
        </div>
        <div id="tab2" class="weui-tab__bd-item" data-url="/cate/list">
        </div>
        <div id="tab3" class="weui-tab__bd-item" data-url="/order/cart">
        </div>
        <div id="tab4" class="weui-tab__bd-item" data-url="/index/me">
        </div>
      </div>

      <div class="weui-tabbar">
          <a href="/" class="weui-tabbar__item @if ($nav == 1)weui-bar__item--on @endif">
          <div class="weui-tabbar__icon">
            <img src="/static/img/main.png" alt="">
          </div>
          <p class="weui-tabbar__label">首页</p>
        </a>
        <a href="/?nav=2" class="weui-tabbar__item @if ($nav == 2)weui-bar__item--on @endif">
          <div class="weui-tabbar__icon">
            <img src="/static/img/cate.png" alt="">
          </div>
          <p class="weui-tabbar__label">分类</p>
        </a>
        <a href="/?nav=3" class="weui-tabbar__item @if ($nav == 3)weui-bar__item--on @endif">
          <div class="weui-tabbar__icon">
            <img src="/static/img/cart.png" alt="">
          </div>
          <p class="weui-tabbar__label">购物车</p>
        </a>
        <a href="/?nav=4" class="weui-tabbar__item @if ($nav == 4)weui-bar__item--on @endif">
          <div class="weui-tabbar__icon">
            <img src="/static/img/me.png" alt="">
          </div>
          <p class="weui-tabbar__label">我</p>
        </a>
      </div>
    </div>
    <a href="https://chat.sobot.com/chat/h5/v2/index.html?sysnum=deb92d94bba240c6911fe44affb4e7d8&channelid=2"><div id="m-toke"></div></a>
    <script src="https://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js"></script>
    <script src="/static/layer_mobile/layer.js"></script>
    <script src="/js/fastclick.js"></script>
    <script src="/js/main.js?time={{ time() }}"></script>
    <script>
        @auth
            loginIn();
        @endauth
        checkCache();
        FastClick.attach(document.body);
        var hash = '{{ $nav }}';//必须是全局变量
        var his = historyUrl(1), oldhash = '';
        if(his) oldhash = his.substr(0,1);
        if(isEmpty(hash) || (hash !== '1' && hash !== '2' && hash !== '3' && hash !== '4')){
            hash = '1';
        }
        var url = oldhash == hash ? his.substr(1) : $('#tab'+hash).attr('data-url');
        $('#tab1').append('<iframe src="'+url+'"></iframe>');
        $('.weui-tabbar__item').on('click', function(){
            historyUrl(2);
        });
    </script>
</body>
</html>