<!DOCTYPE>
<html lang="zh-CN">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>amief</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css">
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
        <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
        <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .weui-tab iframe{width: 100%; height: 100%; border: none;padding-bottom: 55px;}
    </style>
</head>

<body ontouchstart>
    <div class="weui-tab">
      <div class="weui-tab__bd">
        <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">
            <iframe src="main"></iframe>
        </div>
        <div id="tab2" class="weui-tab__bd-item">
          <iframe src="search"></iframe>
        </div>
        <div id="tab3" class="weui-tab__bd-item">
          <h1>开发中...</h1>
        </div>
        <div id="tab4" class="weui-tab__bd-item">
          <h1>开发中...</h1>
        </div>
      </div>

      <div class="weui-tabbar">
          <a href="#tab1" class="weui-tabbar__item weui-bar__item--on" href="#tab1">
          <div class="weui-tabbar__icon">
            <img src="/static/img/main.png" alt="">
          </div>
          <p class="weui-tabbar__label">首页</p>
        </a>
        <a href="#tab2" class="weui-tabbar__item" href="#tab2">
          <div class="weui-tabbar__icon">
            <img src="/static/img/hot.png" alt="">
          </div>
          <p class="weui-tabbar__label">热门</p>
        </a>
        <a href="#tab3" class="weui-tabbar__item" href="#tab3">
          <div class="weui-tabbar__icon">
            <img src="/static/img/see.png" alt="">
          </div>
          <p class="weui-tabbar__label">发现</p>
        </a>
        <a href="#tab4" class="weui-tabbar__item" href="#tab4">
          <div class="weui-tabbar__icon">
            <img src="/static/img/me.png" alt="">
          </div>
          <p class="weui-tabbar__label">我</p>
        </a>
      </div>
    </div>

    <script src="https://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js"></script>
    <script src="/js/fastclick.js"></script>
    <script>
        $(function() {
            FastClick.attach(document.body);
        });
    </script>
</body>    
</html>