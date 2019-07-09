<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>amief</title>
    <link rel="stylesheet" type="text/css" href="/static/plugs/jigsaw/jigsaw.css">
    <script src="/static/plugs/jigsaw/jigsaw.min.js"></script>
    <style>
        body{
             height: 100%;
             overflow: hidden;
             background-image: url('/static/img/x.png')
        }
        .container {
          width: 330px;
          margin: 100px auto;
        }
        #captchaes {
            padding: 10px;
            border-radius: 5px;
            position: relative;
            background: linear-gradient(to bottom right, red,orange,yellow,green,blue,indigo,violet);
            animation-delay:2s;
            animation-timing-function: ease-in-out;
            animation:mymove 5s;
        }
        #msg {
          width: 100%;
          line-height: 30px;
          font-size: 14px;
          text-align: center;
        }
        @keyframes mymove
        {
            0%   {background:red; left:-100px; top:0px;transform: rotate(0deg) translateZ(0);}
            25%  {background:yellow; left:100px; top:0px;transform: rotate(90deg) translateZ(0);}
            50%  {background:blue; left:100px; top:200px;transform: rotate(180deg) translateZ(0);}
            75%  {background:green; left:-100px; top:200px;transform: rotate(270deg) translateZ(0);}
            100% {background:red; left:0px; top:0px;transform: rotate(0deg) translateZ(0);}
        }
    </style>
</head>

<body>
    <div class="container">
        <div id="captchaes">
            <div id="captcha"></div>
            <div id="msg"></div>
        </div>
    </div>

    <script>
        jigsaw.init({
            el: document.getElementById('captcha'),
            onSuccess: function() {
                sessionStorage.setItem("lses", 'WDN@*DS');
                document.getElementById('msg').innerHTML = '验证成功';
                setTimeout(function(){
                    location.href = '/csij/dso3/1dksl/dcns';
                },500);
            },
            onFail: cleanMsg,
            onRefresh: cleanMsg
        });
        function cleanMsg() {
            document.getElementById('msg').innerHTML = '';
        }
    </script>
</body>    
</html>