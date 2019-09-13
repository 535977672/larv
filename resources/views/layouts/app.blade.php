<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>优甜缘 - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css">
    <link rel="stylesheet" href="/static/layer_mobile/need/layer.css">
    <link rel="stylesheet" href="/static/css/comm.css?time={{ time() }}">
    <link rel="stylesheet" href="/static/css/main.css?time={{ time() }}">
    <!-- 让IE8/9支持媒体查询 从而兼容栅格 -->
    <!--[if lt IE 9]>
        <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
        <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body{width: 100%; overflow-x: hidden;}
        .container{padding-bottom: 53px;}
        .bgc-ec{background-color: {{ getTheme() }};}
    </style>
    @yield('head')
</head>

<body>
    @yield('content')
    <script src="https://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js"></script>
    <script src="/static/layer_mobile/layer.js"></script>
    <script src="/js/fastclick.js"></script>
    <script src="/js/main.js?time={{ time() }}"></script>
    <script>
        @auth
            loginIn();
        @endauth
        $(function() {
            FastClick.attach(document.body);
            historyUrl();
        });
    </script>
    @yield('script')
</body>    
</html>

