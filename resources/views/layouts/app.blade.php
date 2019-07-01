<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>amief - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css">
    <link rel="stylesheet" href="/static/css/main.css">
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
        <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
        <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body{position: absolute; width: 100%; overflow-x: hidden; height: 100%; overflow-y: scroll;}
    </style>
</head>

<body>
    @if (!$isWeixin)
	<div class="weui-pull-to-refresh__layer">
		<div class='weui-pull-to-refresh__arrow'></div>
		<div class='weui-pull-to-refresh__preloader'></div>
		<div class="down">下拉刷新</div>
		<div class="up">释放刷新</div>
		<div class="refresh">正在刷新</div>
	</div>
    @endif
    @yield('content')
    <script src="https://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js"></script>
    <script src="/js/jquery.lazyload.min.js"></script>
    <script src="/js/fastclick.js"></script>
    <script src="/js/main.js"></script>
    <script>
        $(function() {
            FastClick.attach(document.body);
        });
        @if (!$isWeixin)
        $(document.body).pullToRefresh(function () {
			winReload();
            $(document.body).pullToRefreshDone();
		});
        @endif
        lazyload("img.lazy");
    </script>
    @yield('script')
</body>    
</html>