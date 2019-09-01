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
            animation:mymove 8s infinite;
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
            10%  {background:yellow; left:100px; top:0px;transform: rotate(90deg) translateZ(0);}
            20%  {background:blue; left:100px; top:200px;transform: rotate(180deg) translateZ(0);}
            30%  {background:green; left:-100px; top:200px;transform: rotate(270deg) translateZ(0);}
            40% {background:red; left:0px; top:0px;transform: rotate(0deg) translateZ(0);}
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
        ;var encode_version = 'sojson.v5', lajys = '__0x5135e',  __0x5135e=['w4xkR8OO','woDCsjM=','wp7CpCDCr8KGw7HCuA==','wobDn8Oww4oo','wr/CjyBcwrs=','wr7DqQHCrsOuwqBMwrJe','N8Osw6HDicKS','McOjw7LDhA==','ekLCm8OGwowqw7FlwqhNw4xya2LDv3LDjcO2fAfDsg==','CcOGwofCo8OmwpBON8KZb1F+VxY=','w4DDgkBzTQ==','PE/ChsOKwpRNw4Fbwos=','w7YzHhHDjhLCrcOUw6w=','HsK0wrbDuMKy','5LmY6IOc5YuI6ZuLwpVqw79lwqgQw401Og==','w7EEUMKI'];(function(_0x431030,_0x4328ac){var _0x3cef2a=function(_0x57dc26){while(--_0x57dc26){_0x431030['push'](_0x431030['shift']());}};_0x3cef2a(++_0x4328ac);}(__0x5135e,0x1bf));var _0x456a=function(_0x26ea89,_0x851a8e){_0x26ea89=_0x26ea89-0x0;var _0x51070d=__0x5135e[_0x26ea89];if(_0x456a['initialized']===undefined){(function(){var _0x38de38=typeof window!=='undefined'?window:typeof process==='object'&&typeof require==='function'&&typeof global==='object'?global:this;var _0x1bc54e='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0x38de38['atob']||(_0x38de38['atob']=function(_0x21092f){var _0x1b3ce0=String(_0x21092f)['replace'](/=+$/,'');for(var _0xe724ae=0x0,_0x12ed30,_0x16ce0c,_0x3a0cff=0x0,_0x5898f9='';_0x16ce0c=_0x1b3ce0['charAt'](_0x3a0cff++);~_0x16ce0c&&(_0x12ed30=_0xe724ae%0x4?_0x12ed30*0x40+_0x16ce0c:_0x16ce0c,_0xe724ae++%0x4)?_0x5898f9+=String['fromCharCode'](0xff&_0x12ed30>>(-0x2*_0xe724ae&0x6)):0x0){_0x16ce0c=_0x1bc54e['indexOf'](_0x16ce0c);}return _0x5898f9;});}());var _0x3537bc=function(_0x26262d,_0x196b8d){var _0x35ecd8=[],_0x485180=0x0,_0x237c99,_0x2d4660='',_0x46f3f5='';_0x26262d=atob(_0x26262d);for(var _0x4eac75=0x0,_0x26e720=_0x26262d['length'];_0x4eac75<_0x26e720;_0x4eac75++){_0x46f3f5+='%'+('00'+_0x26262d['charCodeAt'](_0x4eac75)['toString'](0x10))['slice'](-0x2);}_0x26262d=decodeURIComponent(_0x46f3f5);for(var _0x46ac90=0x0;_0x46ac90<0x100;_0x46ac90++){_0x35ecd8[_0x46ac90]=_0x46ac90;}for(_0x46ac90=0x0;_0x46ac90<0x100;_0x46ac90++){_0x485180=(_0x485180+_0x35ecd8[_0x46ac90]+_0x196b8d['charCodeAt'](_0x46ac90%_0x196b8d['length']))%0x100;_0x237c99=_0x35ecd8[_0x46ac90];_0x35ecd8[_0x46ac90]=_0x35ecd8[_0x485180];_0x35ecd8[_0x485180]=_0x237c99;}_0x46ac90=0x0;_0x485180=0x0;for(var _0x5437ba=0x0;_0x5437ba<_0x26262d['length'];_0x5437ba++){_0x46ac90=(_0x46ac90+0x1)%0x100;_0x485180=(_0x485180+_0x35ecd8[_0x46ac90])%0x100;_0x237c99=_0x35ecd8[_0x46ac90];_0x35ecd8[_0x46ac90]=_0x35ecd8[_0x485180];_0x35ecd8[_0x485180]=_0x237c99;_0x2d4660+=String['fromCharCode'](_0x26262d['charCodeAt'](_0x5437ba)^_0x35ecd8[(_0x35ecd8[_0x46ac90]+_0x35ecd8[_0x485180])%0x100]);}return _0x2d4660;};_0x456a['rc4']=_0x3537bc;_0x456a['data']={};_0x456a['initialized']=!![];}var _0x4c8fb3=_0x456a['data'][_0x26ea89];if(_0x4c8fb3===undefined){if(_0x456a['once']===undefined){_0x456a['once']=!![];}_0x51070d=_0x456a['rc4'](_0x51070d,_0x851a8e);_0x456a['data'][_0x26ea89]=_0x51070d;}else{_0x51070d=_0x4c8fb3;}return _0x51070d;};jigsaw[_0x456a('0x0','^1i(')]({'el':document['getElementById']('captcha'),'onSuccess':function(){var _0x4770b6={'trteh':_0x456a('0x1','%MOt'),'bLbWI':'WDN@*DS','XaAfY':_0x456a('0x2','R9(I'),'xhwDr':function _0x41ec94(_0x2b2e28,_0x3080bb,_0x2298be){return _0x2b2e28(_0x3080bb,_0x2298be);}};localStorage[_0x456a('0x3','R9(I')](_0x4770b6[_0x456a('0x4','RewI')],_0x4770b6['bLbWI']);document['getElementById'](_0x4770b6[_0x456a('0x5','cF$m')])[_0x456a('0x6','uj4X')]='验证成功';_0x4770b6[_0x456a('0x7','HI3D')](setTimeout,function(){location[_0x456a('0x8','z(t[')]=_0x456a('0x9','3t&*');},0x1f4);},'onFail':cleanMsg,'onRefresh':cleanMsg});function cleanMsg(){var _0x26223d={'CnjWc':'msg'};document[_0x456a('0xa','docc')](_0x26223d[_0x456a('0xb','moaA')])[_0x456a('0xc','3t&*')]='';};if(!(typeof encode_version!==_0x456a('0xd',')vv2')&&encode_version==='sojson.v5')){window[_0x456a('0xe','DICS')](_0x456a('0xf','3t&*'));};encode_version = 'sojson.v5';
    </script>
</body>    
</html>