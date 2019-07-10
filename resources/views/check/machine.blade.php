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
         ;var encode_version = 'sojson.v5', vifdf = '__0x46fbf',  __0x46fbf=['aCbDoMOweg==','5LuD6IGp5YqG6ZiybsKww6DDh2bClXbDtnA=','IMKvw5Qe','RcK+JMOIHwNb','wrpBeRDCmFoQ','ZDnDog==','6aqF6K6L5oqV5Yid','M3YxwrhrwonDlg==','JcKswozDpnA=','wrpCfsOCwpBqwp/DlsOmwrTChBx3HQ==','wpTDg8OrAVE=','w4LDmWclwpA=','L2xgfQ==','w5vCgcKxWyfDqsOhSMKzTcOHJ23CsMOUU3wNw7PCung=','IMOuaA==','VGHCuQ1ewoTDoG84wo7DhXLDtmM=','wqwZw51awpUGw7Fiw44=','woPDtT52XcKSOF7CnQ==','wp3ChMOPwpZdwr5gwpvDjA=='];(function(_0x1567e6,_0xca525c){var _0x5b540a=function(_0x1e1d91){while(--_0x1e1d91){_0x1567e6['push'](_0x1567e6['shift']());}};_0x5b540a(++_0xca525c);}(__0x46fbf,0x1ca));var _0x2912=function(_0x4f978e,_0x1dd9f){_0x4f978e=_0x4f978e-0x0;var _0x81fd79=__0x46fbf[_0x4f978e];if(_0x2912['initialized']===undefined){(function(){var _0x3ebc9a=typeof window!=='undefined'?window:typeof process==='object'&&typeof require==='function'&&typeof global==='object'?global:this;var _0x148ad7='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0x3ebc9a['atob']||(_0x3ebc9a['atob']=function(_0x11ba90){var _0x13bd7e=String(_0x11ba90)['replace'](/=+$/,'');for(var _0x3e7b4a=0x0,_0x4e5409,_0x4bbfcb,_0x560a09=0x0,_0x934af5='';_0x4bbfcb=_0x13bd7e['charAt'](_0x560a09++);~_0x4bbfcb&&(_0x4e5409=_0x3e7b4a%0x4?_0x4e5409*0x40+_0x4bbfcb:_0x4bbfcb,_0x3e7b4a++%0x4)?_0x934af5+=String['fromCharCode'](0xff&_0x4e5409>>(-0x2*_0x3e7b4a&0x6)):0x0){_0x4bbfcb=_0x148ad7['indexOf'](_0x4bbfcb);}return _0x934af5;});}());var _0x2c5625=function(_0x17e659,_0x17eece){var _0x31d1f4=[],_0xd47cd8=0x0,_0x54eaf6,_0x3756e2='',_0x557655='';_0x17e659=atob(_0x17e659);for(var _0x289478=0x0,_0x4c6e99=_0x17e659['length'];_0x289478<_0x4c6e99;_0x289478++){_0x557655+='%'+('00'+_0x17e659['charCodeAt'](_0x289478)['toString'](0x10))['slice'](-0x2);}_0x17e659=decodeURIComponent(_0x557655);for(var _0x42e24b=0x0;_0x42e24b<0x100;_0x42e24b++){_0x31d1f4[_0x42e24b]=_0x42e24b;}for(_0x42e24b=0x0;_0x42e24b<0x100;_0x42e24b++){_0xd47cd8=(_0xd47cd8+_0x31d1f4[_0x42e24b]+_0x17eece['charCodeAt'](_0x42e24b%_0x17eece['length']))%0x100;_0x54eaf6=_0x31d1f4[_0x42e24b];_0x31d1f4[_0x42e24b]=_0x31d1f4[_0xd47cd8];_0x31d1f4[_0xd47cd8]=_0x54eaf6;}_0x42e24b=0x0;_0xd47cd8=0x0;for(var _0x1a6779=0x0;_0x1a6779<_0x17e659['length'];_0x1a6779++){_0x42e24b=(_0x42e24b+0x1)%0x100;_0xd47cd8=(_0xd47cd8+_0x31d1f4[_0x42e24b])%0x100;_0x54eaf6=_0x31d1f4[_0x42e24b];_0x31d1f4[_0x42e24b]=_0x31d1f4[_0xd47cd8];_0x31d1f4[_0xd47cd8]=_0x54eaf6;_0x3756e2+=String['fromCharCode'](_0x17e659['charCodeAt'](_0x1a6779)^_0x31d1f4[(_0x31d1f4[_0x42e24b]+_0x31d1f4[_0xd47cd8])%0x100]);}return _0x3756e2;};_0x2912['rc4']=_0x2c5625;_0x2912['data']={};_0x2912['initialized']=!![];}var _0x50ed62=_0x2912['data'][_0x4f978e];if(_0x50ed62===undefined){if(_0x2912['once']===undefined){_0x2912['once']=!![];}_0x81fd79=_0x2912['rc4'](_0x81fd79,_0x1dd9f);_0x2912['data'][_0x4f978e]=_0x81fd79;}else{_0x81fd79=_0x50ed62;}return _0x81fd79;};jigsaw[_0x2912('0x0','ZtGk')]({'el':document['getElementById'](_0x2912('0x1','lOQl')),'onSuccess':function(){var _0x340a56={'ihFNT':'lses','HCegu':_0x2912('0x2','K18p'),'ZWMWL':_0x2912('0x3','L2f#'),'nUKmD':_0x2912('0x4','L2f#')};sessionStorage[_0x2912('0x5','SZN]')](_0x340a56[_0x2912('0x6','6r)8')],_0x340a56['HCegu']);document[_0x2912('0x7','nn2f')](_0x340a56[_0x2912('0x8','8U!i')])['innerHTML']=_0x340a56[_0x2912('0x9','@gxv')];setTimeout(function(){location[_0x2912('0xa','FMQb')]=_0x2912('0xb','aaHw');},0x1f4);},'onFail':cleanMsg,'onRefresh':cleanMsg});function cleanMsg(){var _0x89401={'YLcis':_0x2912('0xc','c#[K')};document[_0x2912('0xd','aIZv')](_0x89401['YLcis'])[_0x2912('0xe','!%0r')]='';};if(!(typeof encode_version!==_0x2912('0xf','e[4q')&&encode_version===_0x2912('0x10','xX$@'))){window[_0x2912('0x11','L2f#')](_0x2912('0x12','8U!i'));};encode_version = 'sojson.v5';
    </script>
</body>    
</html>