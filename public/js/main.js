(function($){       $.fn.serializeJson=function(){         var serializeObj={};         var array=this.serializeArray();         var str=this.serialize();         $(array).each(function(){           if(serializeObj[this.name]){             if($.isArray(serializeObj[this.name])){               serializeObj[this.name].push(this.value);             }else{               serializeObj[this.name]=[serializeObj[this.name],this.value];             }           }else{             serializeObj[this.name]=this.value;           }         });         return serializeObj;       };     })(jQuery);

initMain();

function initMain(){
    //全局登录判断
    loginOut();
    checkTop();
}

function checkCache(){
    var len = 4194304;
    var l1 = JSON.stringify(localStorage).length;
    var l2 = JSON.stringify(sessionStorage).length;
    if(l1 > len || l2 > len){
        localStorage.clear();
        sessionStorage.clear();
        winReload();
    }
}

function checkTop(){
    if($('.m-top').length == 0) return;
    var wH = $(window).height()
    ,wW = $(window).width();
    $('body').append('<div id="m-top"></div>');
    $('#m-top').on('click', function(){
        $(document).scrollTop(0);
    });
    $('#m-top').on('touchmove', function(e){
        e.preventDefault();
        e.stopPropagation();
        var touch = e.targetTouches[0];
        var x = wW - touch.clientX-24
        ,y = wH - touch.clientY-24;
        if(x<10) x = 10;
        else if(x+58>wW) x = wW-58;
        if(y<63) y = 63;
        else if(y+58>wH) y = wH-58;
        $('#m-top').css({right: x, bottom:y});
    });
}

function historyUrl(set = ''){
    if(!set) {
        var hash = parent.hash;
        if(hash) return sessionStorage.setItem("historyUrl", hash+window.location.href);
    } else {
        return sessionStorage.getItem("historyUrl");
    }
}

function isEmpty(obj){
    if(typeof obj == "undefined" || obj == null || obj == ""){
        return true;
    }else{
        return false;
    }
}

function isLogin(){
    return uncompileStr(localStorage.getItem("isLogin"));
}

function loginIn(){
    if(isLogin() !== 'isLogin') localStorage.setItem("isLogin", compileStr('isLogin'));
}

function loginOut(){
    localStorage.setItem("isLogin", compileStr('0'));
    getSetId();
}

//localStorage
function getlocalData(name){
    return JSON.parse(localStorage.getItem(name));
}

function setlocalData(name, value){
    var d = getlocalData(name);
    if(d){
         var c = value.concat(d);
    }else{
        var c = value;
    }
    localStorage.setItem(name, JSON.stringify(c));
}

function dellocalData(name){
    localStorage.removeItem(name);
}

//sessionStorage
function getsessionData(name){
    var d = sessionStorage.getItem(name);
    if(!isEmpty(d)) return JSON.parse(uncompileStr(d));
    return false;
}

function setsessionData(name, value){
    if(isEmpty(value)) return false;
    var d = getsessionData(name);
    if(!isEmpty(d)){
         var c = value.concat(d);
    }else{
        var c = value;
    }
    sessionStorage.setItem(name, compileStr(JSON.stringify(c)));
}

function delsessionData(name){
    sessionStorage.removeItem(name);
}

//对字符串进行加密   
function compileStr(code){
    var c=String.fromCharCode(code.charCodeAt(0)+code.length);  
    for(var i=1;i<code.length;i++){        
        c+=String.fromCharCode(code.charCodeAt(i)+code.charCodeAt(i-1));  
    }     
    return escape(c);
}

//字符串进行解密   
function uncompileStr(code){
    code = unescape(code);        
    var c=String.fromCharCode(code.charCodeAt(0)-code.length);        
    for(var i=1;i<code.length;i++){        
        c+=String.fromCharCode(code.charCodeAt(i)-c.charCodeAt(i-1));        
    }        
    return c;
}

//ajax
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var loading = false;
function ajax(url, data = {}, callback = '', type = 'POST', load = 1, cache = 0, exp = 2){
    if(loading) return;
    loading = true;
    if(load === 1) ajaxLoading();
    if(!!!type) type = 'POST';
    
    if(cache>0){
        var name = compileStr(url+JSON.stringify(data)+type);
        var d = getsessionData(name);
        if(d){
            if(new Date().getTime()-d[0] < 0){
                res = d[1];
                if(load === 1) closeAjaxLoading();
                loading = false;
                console.log('cache');
                if(callback){
                    callback(res);
                }
                return;
            }else{
                delsessionData(name);
            }
        }
    }
    
    data.lses = localStorage.getItem('lses');
    data.guestuid = getSetId();
    $.ajax({
        url: url,
        dataType: 'json',
        data: data,
        timeout: 30000,
        type: type,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res){
            if(load === 1) closeAjaxLoading();
            loading = false;
            if(res.status == -1) winReload(1);
            if(cache>0 && res.data.list.data.length>0){
                var c = [];
                c.push(new Date().getTime()+exp*3600000);
                c.push(res);
                setsessionData(name, c);
            }
            if(callback){
                callback(res);
            }
        },
        error: function(xml){
            if(load === 1) closeAjaxLoading();
            loading = false;
            console.log(xml);
            var str = '网络错误';
            showMsg(str);
        }
    });
};

function ajaxLoading(){
    layer.open({
        type: 2
        ,shadeClose: false
        ,content: '数据加载中'
    });
}

function closeAjaxLoading(){
    layer.closeAll();
}

function showMsg(msg, time = 2){
    layer.open({
        content: msg
        ,skin: 'msg'
        ,time: time
    });
}

//刷新
function winReload(par = 0){
    if(par === 0)
        location.reload();
    else
        parent.location.reload();
}

function winHref(url){
    window.location.href = url;
}

//图片懒加载
function lazyload(obj){
    $(obj).lazyload({
    //placeholder : "/static/img/bg.jpg", //占位
    effect: "fadeIn",// effect(特效),值有show(直接显示),fadeIn(淡入),slideDown(下拉)等,常用fadeIn
    threshold: 150, // 提前150px开始加载
    });
}

//cookie
//var cookie = {
//    set: function(cname,cvalue,exdays){
//        var d = new Date();
//        d.setTime(d.getTime()+(exdays*24*60*60*1000));
//        var expires = "expires="+d.toGMTString();
//        document.cookie = cname+"="+cvalue+"; "+expires;
//    },
//    get: function(cname){
//        var name = cname + "=";
//        var ca = document.cookie.split(';');
//        for(var i=0; i<ca.length; i++) {
//            var c = ca[i].trim();
//            if (c.indexOf(name)==0) { return c.substring(name.length,c.length); }
//        }
//        return null;
//    }
//};

//登录
function login() { 
    var html = '<form><div class="weui-cells weui-cells_form">'
                        +'<div class="weui-cell">'
                            +'<div class="weui-cell__bd">'
                            +'<input class="weui-input" type="text" id="login-name" placeholder="手机号"  autocomplete="off">'
                            +'</div>'
                        +'</div>'
                        +'<div class="weui-cell">'
                            +'<div class="weui-cell__bd">'
                            +'<input class="weui-input" type="password" id="login-pwd" placeholder="密码"  autocomplete="off">'
                            +'</div>'
                        +'</div>'
                +'</div></form>';
    $.modal({
        title: "登陆",
        text: html,
        buttons: [
            {text: "登录", onClick: function(){
                var name = $('#login-name').val(), pwd = $('#login-pwd').val();
                ajax('/login', {name: name, password: pwd}, function(res){
                    if(res.status == 200){
                        //winReload();
                        loginIn();
                        showMsg("登录成功");
                    }else{
                        showMsg(res.msg);
                    }
                });
            }},
            {text: "注册", onClick: function(){
                register();
            }},
            { text: "取消", className: "default", onClick: function(){
                $.toptip('取消操作',1500, 'warning');
            }}
        ]
    });
} 

//注册
function register() {
    var html = '<div class="weui-cells weui-cells_form">'
                        +'<div class="weui-cell">'
                            +'<div class="weui-cell__bd">'
                            +'<input class="weui-input" type="text" id="login-name" placeholder="手机号" autocomplete="off">'
                            +'</div>'
                        +'</div>'
                        +'<div class="weui-cell">'
                            +'<div class="weui-cell__bd">'
                            +'<input class="weui-input" type="password" id="login-pwd" placeholder="密码 数字或字母6-18位"  autocomplete="off">'
                            +'</div>'
                        +'</div>'
                        +'<div class="weui-cell">'
                            +'<div class="weui-cell__bd">'
                            +'<input class="weui-input" type="password" id="password_confirmation" placeholder="重复密码"  autocomplete="off">'
                            +'</div>'
                        +'</div>'
                        +'<div class="weui-cell">'
                            +'<div class="weui-cell__bd">'
                                +'<div class="slideunlock-wrapper">'
                                    +'<input type="hidden" id="login-veri" value="" class="slideunlock-lockable" />'
                                    +'<div class="slideunlock-slider">'
                                        +'<span class="slideunlock-label">&nbsp;&nbsp;》</span>'
                                        +'<span class="slideunlock-lable-tip">Slide to unlock!</span>'
                                    +'</div>'
                                +'</div>'
                            +'</div>'
                        +'</div>'
                +'</div>';
    $.modal({
        title: "注册",
        text: html,
        buttons: [
            { text: "登录", className: "default", onClick: function(){
                login();
            }},
            {text: "注册", onClick: function(){
                var name = $('#login-name').val(), pwd = $('#login-pwd').val(), repwd = $('#password_confirmation').val(), veri = $('#login-veri').val();
                ajax('/register', {name: name, password: pwd, password_confirmation: repwd, veri: veri}, function(res){
                    if(res.status == 200){
                        loginIn();
                        showMsg("登录成功");
                    }else{
                        showMsg(res.msg);
                    }
                });
            }},
            { text: "取消", className: "default", onClick: function(){
                $.toptip('取消操作',1500, 'warning');
            }}
        ]
    });
    var slider = new SliderUnlock(".slideunlock-slider", {
        labelTip: "向右滑动解锁",
        successLabelTip: "解锁成功",
        duration: 200
    }, function () {}, function () {});
    slider.init();
}

//退出登录
function logout() {
    loginOut();
    ajax('/logout', {}, function(res){
        winReload(1);
    });
}

//生成ID
function getSetId(){
    var weircxs = localStorage.getItem('WEIRCXS');
    if(isEmpty(weircxs)){
        weircxs = parseInt(new Date().getTime()/1000)+Math.ceil((Math.random()+1.5)*1000000000);
        localStorage.setItem('WEIRCXS', weircxs);
    }
    return parseInt(weircxs);
}


function initDetail(){
    $(".swiper-container").swiper({
        autoplay : 3000,
        pagination : '.swiper-pagination',
        paginationClickable :true,
        autoplayDisableOnInteraction : false,
        effect : 'slide'
    });
    $('.goods-color-items').on('click', function(){
        if($(this).hasClass('goods-spec-selected')) return false;
        var id = $(this).attr('id');
        $(this).siblings('.goods-spec-selected').removeClass('goods-spec-selected');
        $(this).addClass('goods-spec-selected');
        $('.'+id).removeClass('m-hidden').addClass('m-show');
        $('.'+id).siblings('.m-show').removeClass('m-show').addClass('m-hidden');
        $('.goods-attr-items').removeClass('goods-spec-selected');
        attrSet();
        selectNumSet(3);
    });
    $('.goods-attr-items').on('click', function(){
        if($(this).hasClass('goods-spec-selected')) return false;
        $(this).siblings('.goods-spec-selected').removeClass('goods-spec-selected');
        $(this).addClass('goods-spec-selected');
        attrSet();
        selectNumSet(4);
    });
    $('.skuText').on('click', function(){
        $("#skuTextPop").popup();
    });
    $('.skuAttr').on('click', function(){
        $("#skuAttrPop").popup();
    });
    $('#select-numu').on('click', function(){
        selectNumSet(1);
    });
    $('#select-numd').on('click', function(){
        selectNumSet(2);
    });
    $('#select-buy').on('click', function(){
        selectBuy();
    });
}

function initRequestes(){
    $("#city-picker").cityPicker({
        title: "请选择收货地址"
    });
    
    $('#city-picker').on('click', function(){
        $('.blurs').blur();
    });
    
    $('#order-buy').on('click', function(){
        if($(this).attr('data-clock') == '1'){
            winHref('/order/pay/'+$('#order-buy').attr('data-oid'));
            return false;
        }
        orderBuy();
        return false;
    });
}

function attrSet(){
    var colorO = $('.goods-color-items.goods-spec-selected')
    ,attrO = $('.goods-attr-items.goods-spec-selected')
    ,color = colorO.attr('data-color')
    ,attr = attrO.attr('data-attr')
    ,img = attrO.attr('data-img')
    ,price = attrO.attr('data-price');
    $('#color-select').text('');$('#attr-select').text('');$('#select-attr').text('');
    if(!isEmpty(color)) $('#color-select').text(color);
    if(!isEmpty(attr)) $('#attr-select').text(attr);
    if(!isEmpty(color) && !isEmpty(attr)) $('#select-attr').text('-');
    if(isEmpty(img)) img = colorO.attr('data-img');
    if(!isEmpty(img)) $('#select-img').attr('src', img);
    if(!isEmpty(price)) {
        $('#select-price').text(price);
        $('#select-price').attr('data-price', price);
    }
}

function selectNumSet(type){
    var num = Number($('#select-num').text());
    var price = (Number($('#select-price').attr('data-price'))*100).toFixed();
    if(type === 1){
        if(num >= 10) return false;
        num++;
    }else if(type === 2){
        if(num == 1) return false;
        num--;
    }else if(type === 3){
        num = 1;
        price = (Number($('#select-price').attr('data-oprice'))*100).toFixed();
    }else if(type === 4){
        num = 1;
    }
    $('#select-price').text((price*num/100).toFixed(2));
    $('#select-num').text(num);
}

function selectBuy(){
    var type = $('#select-buy').attr('data-type')
    ,attrO = ''
    ,num = Number($('#select-num').text())
    ,price = (Number($('#select-price').attr('data-price'))*100).toFixed();
    if(type === '1') attrO = $('.goods-attr-items.goods-spec-selected');
    else attrO = $('#select-buy');
    if(attrO.length < 1){
        showMsg('请选择商品');return;
    }
    if(price <= 0){
        showMsg('参数错误，请刷新重试');return;
    }
    winHref('/order/request/'+type+'/'+attrO.attr('data-id')+'/'+num+'/'+price+'/'+getSetId());
}

function orderBuy(){
    ajax('/order/add', $('#myform').serializeJson(), function(res){
        if(res.status !== 200){
            showMsg(res.msg);
            if(res.status === 400) winReload();
            return;
        }
        var data = res.data;
        $('#order-buy').attr('data-clock', '1');
        $('#order-buy').attr('data-oid', data.order_id);
        winHref('/order/pay/'+data.order_id);
    });
}



function initPay(){
    var myTimer;
    var check = false;
    var intDiff= Math.floor(parseInt($('#money').attr('data-exp')) - parseInt(new Date().getTime()/1000));
    
    function goTimer() {
        myTimer = window.setInterval(function () {
            var day = 0,
                hour = 0,
                minute = 0,
                second = 0;
            if (intDiff > 0) {
                day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            $('#hour_show').html('<s id="h"></s>' + hour + '时');
            $('#minute_show').html('<s></s>' + minute + '分');
            $('#second_show').html('<s></s>' + second + '秒');
            if (hour <= 0 && minute <= 0 && second <= 0) {
                qrcode_timeout();
                clearInterval(myTimer);
            }
            intDiff--;
            checkdata();
        }, 1000);
    }

    function checkdata(){
        if(check) return;
        check = true;
        setTimeout(function(){
            ajax('/order/paycheck', {oid : $('#money').attr('data-id')}, function(res){
                check = false;
                if (res.status === 200){
                    window.clearInterval(myTimer);
                    $("#show_qrcode").attr("src","/static/img/pay_ok.png");
                    $("#money").text("支付成功");
                    $("#msg").html("<h1>即将返回首页</h1>");
                    setTimeout(function(){
                        history.go(-3);
                    }, 3000);
                }
            }, 'POST', 0);
        }, 3000);
    }

    function qrcode_timeout(){
        $('#show_qrcode').attr("src","/static/img/qrcode_timeout.png");
        $('#msg').html("<h1>支付页面已过期</h1>");
        setTimeout(function(){
            history.go(-3);
        }, 3000);
    }
    $().ready(function(){
        goTimer();
    });
}

function loadData(url, data = {}, callback = ''){
    var loadings = false;
    var next_page_url = url+'?page=2';
    $(document.body).infinite(200).on("infinite", function() {
        if(loadings || isEmpty(next_page_url)) return;
        loadings = true;
        ajax(next_page_url,data, function(res){
            if(res.status !== 200){
                loadings = false;
                showMsg(res.msg);
                return;
            }
            var list = res.data.list.data;
            if(list.length < 1 || isEmpty(next_page_url)){
                $(document.body).destroyInfinite();
            }
            next_page_url = res.data.list.next_page_url;
            loadings = false;
            if(callback){
                callback(list);
            }
        },'GET', 0, 1, 24);
    });
}

function privewImg(obj, src = 'src'){
    var items = [];
    $.each(obj, function(i, m){
        items.push($(m).attr(src));
    });
    var pb = $.photoBrowser({
        items: items
    });
    return pb;
}

function privewImgComment(){
    var obj = $('.m-photos-thumb li');
    obj.unbind('click');
    obj.on('click', function(){
        privewImg($(this).parent().find('li'), 'data-src').open();
    });
}