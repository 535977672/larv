<!DOCTYPE>
<html lang="zh-CN">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>amief</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css">
    <link rel="stylesheet" type="text/css" href="/static/plugs/slideunlock/css/slideunlock.css">
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
        <div id="tab1" class="weui-tab__bd-item" data-url="/index/main">
        </div>
        <div id="tab2" class="weui-tab__bd-item" data-url="/index/search">
        </div>
        <div id="tab3" class="weui-tab__bd-item" data-url="/index/see">
        </div>
        <div id="tab4" class="weui-tab__bd-item" data-url="/index/me">
        </div>
      </div>

      <div class="weui-tabbar">
          <a href="#tab1" class="weui-tabbar__item">
          <div class="weui-tabbar__icon">
            <img src="/static/img/main.png" alt="">
          </div>
          <p class="weui-tabbar__label">首页</p>
        </a>
        <a href="#tab2" class="weui-tabbar__item">
          <div class="weui-tabbar__icon">
            <img src="/static/img/hot.png" alt="">
          </div>
          <p class="weui-tabbar__label">热门</p>
        </a>
        <a href="#tab3" class="weui-tabbar__item">
          <div class="weui-tabbar__icon">
            <img src="/static/img/see.png" alt="">
          </div>
          <p class="weui-tabbar__label">发现</p>
        </a>
        <a href="#tab4" class="weui-tabbar__item" id="login">
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
    <script type="text/javascript" src="/static/plugs/slideunlock/js/jquery.slideunlock.js"></script>
    <script src="/js/main.js?time={{ time() }}"></script>
    <script>
        $(function() {
            @auth
                loginIn();
            @endauth
            FastClick.attach(document.body);
            
            $('.weui-tabbar__item').on('click', function(e){
                var obj = $(this);
                if(obj.attr('href') === '#tab4'){
                    if(isLogin() !== 'isLogin'){
                        e.preventDefault();
                        e.stopPropagation();
                        login();
                        return;
                    }
                }
                var obj_bd = $('#'+obj.attr('href').substr(1));
                if(obj_bd.find('iframe').length === 0){
                    obj_bd.append('<iframe src="'+obj_bd.attr('data-url')+'"></iframe>');
                    $(this).click();
                }
            });
            var hash = window.location.hash;
            if(isEmpty(hash) || (hash !== '#tab1' && hash !== '#tab2' && hash !== '#tab3' && hash !== '#tab4')){
                hash = '#tab1';
            }
            $('.weui-tabbar a[href="'+hash+'"]').click();
        });
    </script>
</body>    
</html>