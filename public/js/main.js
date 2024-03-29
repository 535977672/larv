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
    if(l1 > len){
        localStorage.clear();
    }
    if(l2 > len){
        sessionStorage.clear();
        winReload();
    }
    if(l1 > len || l2 > len){
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
    } else if(set == 1) {
        return sessionStorage.getItem("historyUrl");
    } else {
        return sessionStorage.removeItem("historyUrl");
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
            if(cache>0 && ((res.data.list.hasOwnProperty("current_page") && res.data.list.data.length>0) || (!res.data.list.hasOwnProperty("current_page") && res.data.list.length>0))){
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

function historyBack(n = -1){
    history.go(n);
}

//图片懒加载
function lazyload(obj){
    return;
    $(obj).lazyload({
    //placeholder : "/static/img/bg.jpg", //占位
    effect: "fadeIn",// effect(特效),值有show(直接显示),fadeIn(淡入),slideDown(下拉)等,常用fadeIn
    threshold: 150, // 提前150px开始加载
    });
}

function animOnScrollLoad(id){
    if($('#'+id+' li').length < 1) return;
    new AnimOnScroll(document.getElementById(id), {
        minDuration : 0.4,
        maxDuration : 0.7,
        viewportFactor : 0.2
    });
}

function parseUrl(){
    var url = window.location.href;
    var arr = [];
    if(url.indexOf("?") != -1){
        arr = url.split("?")[1].split("&");
    }
    var obj = {};
    for (var i of arr) {
        obj[i.split("=")[0]] = i.split("=")[1];
    }
    return obj;
}

function loadDataMain(){
    if($('#grid li').length < 1) return;
    $('#grid').addClass('effect-'+(Math.ceil(Math.random()*7)+1));
    animOnScrollLoad('grid');
    var data = parseUrl();
    data.keywords = $('#searchInput').val();
    loadData('/goods/search', data, function(goods){
        var html = '';
        $.each(goods, function(i, g){
            html = html + '<li><div><a href="/goods/detail/'+g.goods_id+'"><img  src="'+g.original_img+'" alt=""><p class="goods-name m-name">'+g.goods_name+'</p><p class="goods-price">¥'+(Number(g.shop_price)/100).toFixed(2)+'</p></a></div></li>';
        });
        $('#grid').append(html);
        animOnScrollLoad('grid');
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
    if(isLogin() == 'isLogin') return;
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
                        loginIn();
                        showMsg("登录成功");
                        winReload();
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
    if(isLogin() == 'isLogin') return;
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
                        winReload();
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
        popup("skuTextPop");
    });
    $('.skuAttr').on('click', function(){
        popup("skuAttrPop");
    });
    $('#select-numu').on('click', function(){
        selectNumSet(1);
    });
    $('#select-numd').on('click', function(){
        selectNumSet(2);
    });
    $('#select-buy').on('click', function(){
        selectBuy(false);
    });
    $('#select-store').on('click', function(){
        selectBuy(true);
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
            winHref('/order/pay/'+$('#order-buy').attr('data-oid')+'?back=-3');
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
        if(num >= 10000) return false;
        num++;
    }else if(type === 2){
        if(num <= 1) return false;
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

function selectBuy($store = false){
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
    //立即购买
    if(!$store) winHref('/order/request/'+type+'/'+attrO.attr('data-id')+'/'+num+'/'+price+'/'+getSetId());
    else {
        //购物车
        $goods = {};
        $goods.type = type;
        $goods.cart_id = attrO.attr('data-id');
        $goods.num = num;
        $goods.price = price;
        $goods.name = $('.goods-items .goods-name').text();
        $goods.spec = $('#spec-select').text();
        $goods.privew = $('#select-img').attr('src');
        $goods.total = num*price;
        $goods.goods_id =  $('#select-store').attr('data-goodsId');
        setCartGoods($goods);
        showMsg("加入购物车成功");
    }
}

function orderBuy(){
    ajax('/order/add', $('#myform').serializeJson(), function(res){
        if(res.status !== 200){
            showMsg(res.msg);
            //if(res.status === 400) winReload();
            //if(res.status === 401) historyBack(-1);
            return;
        }
        var data = res.data;
        $('#order-buy').attr('data-clock', '1');
        $('#order-buy').attr('data-oid', data.order_id);
        setOrderGoods(data);
        if($('#scode').length) delAllCartGoods();
        winHref('/order/pay/'+data.order_id+'?back=-3');
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
            if (hour <=0 && minute <= 0 && second <= 0) {
                qrcode_timeout();
                clearInterval(myTimer);
            }
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            $('#hour_show').html('<s id="h"></s>' + hour + '时');
            $('#minute_show').html('<s></s>' + minute + '分');
            $('#second_show').html('<s></s>' + second + '秒');
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
                        historyBack(window.location.href.substr(-2));
                    }, 3000);
                }
            }, 'POST', 0);
        }, 3000);
    }

    function qrcode_timeout(){
        $('#show_qrcode').attr("src","/static/img/qrcode_timeout.png");
        $('#msg').html("<h1>支付页面已过期</h1>");
        setTimeout(function(){
            //historyBack(window.location.href.substr(-2));
        }, 3000);
    }
    $().ready(function(){
        goTimer();
    });
}

function loadData(url, data = {}, callback = '',type = 'GET', load = 0, cache = 1, exp = 24){
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
        }, type, load, cache, exp);
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

function initOrder(){
    $('.order-detail').unbind('click');
    $('.order-pay').unbind('click');
    $('.order-detail').on('click', function(){
        winHref('/order/detail/'+$(this).attr('data-id')+'/'+getSetId());
    });
    $('.order-pay').on('click', function(){
        winHref('/order/pay/'+$(this).attr('data-id')+'?back='+$(this).attr('data-back'));
    });
    $('.order-del').on('click', function(){
        var id = $(this).attr('data-id');
        $.confirm("确认删除", function() {
            if(isLogin() !== 'isLogin'){
                delOrderGoods(id);
            }
            ajax('/order/del/'+id, {}, function(res){
                if(res.status === 200){
                    showMsg("删除成功");
                    historyBack(-2);
                    winReload();
                }else{
                    showMsg(res.msg);
                }
            });
        }, function() {});
    });
    $('.order-shipping').on('click', function(){
        var url = window.location.protocol+"//"+window.location.host+'?nav=4';
        winHref('https://m.kuaidi100.com/app/query/?com=&nu='+$(this).attr('data-code')+'&coname=meizu&callbackurl='+encodeURI(url));
    });
    $('.order-quest').on('click', function(){
        var id = $(this).attr('data-id');
        $.confirm("确认收货", function() {
            ajax('/order/quest/'+id, {}, function(res){
                if(res.status === 200){
                    showMsg("确认收货成功");
                    winReload();
                }else{
                    showMsg(res.msg);
                }
            });
        }, function() {});
    });
}

function initOrderList($type){
    if($type === 1){
        loadData('/order/list', {}, function(goods){
            $('.weui-panel').append(orderListHtml(goods, $type));
            initOrder();
        }, 'GET', 0, 0);
    }else if($type === 2){
       $('.weui-panel').append(orderListHtml(getOrderGoods(), $type)); 
    }
    initOrder();
}

function getOrderGoods(){
   return getlocalData('5e4e49abda5c7cf487706b81d17d8ab7');
}

function delAllOrderGoods(){
   dellocalData('5e4e49abda5c7cf487706b81d17d8ab7');
}

function delOrderGoods(orderId){
    var goods = getOrderGoods();
    goods = goods.filter(function(item){ return item.order_id != orderId;});
    delAllOrderGoods();
    if(goods.length>0) setlocalData('5e4e49abda5c7cf487706b81d17d8ab7', goods);
    return true;
}

function setOrderGoods($goods){
    if($goods.length<2) return;
    setlocalData('5e4e49abda5c7cf487706b81d17d8ab7', [$goods]);
}

function orderListHtml(goods, type){
    var html = '';
    $.each(goods, function(i, g){
		if(isEmpty(g.order_sn)) return true;
        html += '<div><div class="weui-panel__hd mt10">订单'+g.order_sn+' <span class="m-fr">'+g.add_time+'</span></div>'
                    +'<div class="weui-panel__bd">';
            $.each(g.ordergoods, function(j, gg){
                html += '<a href="javascript:void(0);" data-id="'+g.order_id+'" class="weui-media-box weui-media-box_appmsg order-detail">'
                            +'<div class="weui-media-box__hd">'
                                +'<img class="weui-media-box__thumb" src="'+gg.img+'">'
                            +'</div>'
                            +'<div class="weui-media-box__bd">'
                                +'<h4 class="weui-media-box__title f-14 m-name">'+gg.goods_name+'</h4>'
                                +'<p class="weui-media-box__desc">规格 '+gg.spec_key+' x'+gg.goods_num+'</p>'
                            +'</div>'
                        +'</a>';
            });
        html += '</div>'; 
        if(type === 1 && g.pay_status != 1){
            html += '<div class="weui-panel__ft">'
                        +'<a href="javascript:void(0);" class="weui-cell weui-cell_access weui-cell_link">'
                            +'<div class="weui-cell__bd">'
                                +'<bottom class="weui-btn weui-btn_mini weui-btn_default m-fr order-pay" data-id="'+g.order_id+'" data-back="-1">待支付</bottom>'
                            +'</div>'
                        +'</a>'
                    +'</div>'; 
        }
        html += '</div>';
    });
    return html;
}

function initMe(){
    $('#logout').on('click', function(){
        logout();
    });
    $('#login').on('click', function(){
        login();
    });
    $('#theme-chose').on('click', function(){
        popup("themeAttrPop");
    });
    $('.m-theme').on('click', function(){
        var theme = $(this).text();
        $.confirm("确认主题", function() {
            ajax('/member/theme/'+theme, {}, function(res){
                showMsg(res.msg);
                winReload();
            }, 'GET');
        }, function() {});
    });
}

function popup(id){
    $("#"+id).popup();
    document.body.style.overflow = 'hidden';
    $("#"+id+" .weui-popup__overlay,.close-popup").on('click', function(){
        document.body.style.overflow = 'auto';
    });
}

//购物车
function initCartList($type = 2){
    if($type === 1){

    }else if($type === 2){
        $('.cartlist').append(cartListHtml(getCartGoods(), $type));
    }
    initCart();
}

function initCart(){
    $('#money').text('￥'+(getCartPrice()/100).toFixed(2));
    $('.select-numu').on('click', function(){
        selectCartNumSet(1, $(this));
    });
    $('.select-numd').on('click', function(){
        selectCartNumSet(2, $(this));
    });
    $('#cart-buy').on('click', function(){
        var $oGoods = getCartGoods();
        if(!$oGoods) {
            showMsg('没有商品信息');
            return;
        }
        ajax('/order/requestcart', {attr: $oGoods}, function(res){
            if(res.status == 200){
                var datakey = res.data.datakey;
                winHref('/order/requestcartd/'+datakey);
            }else{
                showMsg(res.msg, 5);
            }
        });
    });
}

function selectCartNumSet(type, obj){
    var sobj = obj.siblings('.select-num'), pobj = $('#cart-price-'+sobj.attr('data-id'));
    var num = Number(sobj.text());
    var price = Number(sobj.attr('data-price'));
    if(type === 1){
        if(num >= 10000) return false;
        num++;
    }else if(type === 2){
        if(num <= 0) return false;
        num--;
    }
    if(num){
        price = (price*num/100).toFixed(2);
        pobj.text(price);
        sobj.text(num);
        updateCartGoods(sobj.attr('data-id'), num);
        $('#money').text('￥'+(getCartPrice()/100).toFixed(2));
    }else{
        delCartGoods(sobj.attr('data-id'));
        obj.parents('.weui-panel__bd').remove();
        $('#money').text('￥'+(getCartPrice()/100).toFixed(2));
    }
}

function getCartPrice(){
    var $oGoods = getCartGoods();
    var price = 0;
    if($oGoods){
        $.each($oGoods, function(i, v){
            price = Number(price)+Number(v.total);
        });
    }
    return price;
}

function cartListHtml(goods){
    var html = '';
    html = '<div class="weui-panel weui-panel_access">';
    $.each(goods, function(i, g){
        if(isEmpty(g.cart_id)) return true;
        html += '<div class="weui-panel__bd">'
                        +'<a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">'
                            +'<div class="weui-media-box__hd">'
                                +'<img class="weui-media-box__thumb" src="'+g.privew+'" onclick="location=\'/goods/detail/'+g.goods_id+'\'">'
                            +'</div>'
                            +'<div class="weui-media-box__bd">'
                                +'<h4 class="weui-media-box__title f-14 m-name">'+g.name+'</h4>'
                                +'<p class="weui-media-box__desc">规格 '+g.spec+'</p>'
                                +'<p class="weui-media-box__desc">金额￥<span class="cart-price" id="cart-price-'+g.cart_id+'">'+Number(g.total/100).toFixed(2)+'</span>'
                                    +'<span class="m-fr"><button class="select-numd">-</button><span class="pl10 pr10 select-num" data-id="'+g.cart_id+'" data-price="'+g.price+'">'+g.num+'</span><button class="select-numu">+</button></span></p>'
                            +'</div>'
                        +'</a>'
                    +'</div>';
    });
    html += '</div>';
    return html;
}

function getCartGoods(){
    return getlocalData('5e4e49abda58uh795gde3wsd87n0vx5g');
}

function delAllCartGoods(){
    dellocalData('5e4e49abda58uh795gde3wsd87n0vx5g');
}

function delCartGoods(cartId){
    var goods = getCartGoods();
    goods = goods.filter(function(item){ return item.cart_id != cartId;});
    delAllCartGoods();
    if(goods.length>0) setlocalData('5e4e49abda58uh795gde3wsd87n0vx5g', goods);
    return true;
}

function setCartGoods($goods){
    $oGoods = getCartGoods();
    if($oGoods){
        var add = true;
        $.each($oGoods, function(i, v){
            if(v.cart_id == $goods.cart_id) {
                v.num = v.num + $goods.num;
                v.price = $goods.price;
                v.total = v.num*v.price;
                add = false;
            }
        });
        if(add) $oGoods = $oGoods.concat($goods);
        delAllCartGoods();
        setlocalData('5e4e49abda58uh795gde3wsd87n0vx5g', $oGoods);
    }else{
        setlocalData('5e4e49abda58uh795gde3wsd87n0vx5g', [$goods]);
    }
}

function updateCartGoods($cartId, $num){
    $oGoods = getCartGoods();
    if($oGoods){
        $.each($oGoods, function(i, v){
            if(v.cart_id == $cartId) {
                v.num = $num;
                v.total = $num * v.price;
            }
        });
        delAllCartGoods();
        setlocalData('5e4e49abda58uh795gde3wsd87n0vx5g', $oGoods);
    }
}

function cate(){
    $(".swiper-container").swiper({
        pagination : '.swiper-pagination',
        paginationClickable :true,
        effect : 'slide',
        slidesPerView :4
    });
    $('.swiper-slide').on('click', function(){
        if($(this).find('div').hasClass('c-cate-s')) return;
        var sex = $(this).attr('data-sex');
        var keywords = $('#searchInputs').val();
        $(this).siblings('div').find('div').removeClass('c-cate-s');
        $(this).find('div').addClass('c-cate-s');
        if(sex>0){
            ajax('/cate/sex/'+sex, {keywords: keywords}, function(res){
                $('.m-cate').html(cateHtml(res.data.list, sex));
            }, 'GET', 0, 1, 1);
        }else{
            ajax('/cate/list/', {keywords: keywords}, function(res){
                $('.m-cate').html(cateHtml(res.data.list, sex));
            }, 'GET', 0, 1, 1);
        }
    });
}

function cateHtml(list, sex){
    var html = '';
    $.each(list, function(i, g){
        if(sex>0){
            html += '<a href="/goods/search?cid='+g.cid+'&sex='+sex+'">'+g.cate.name+'</a>';
        }else{
            html += '<a href="/goods/search?cid='+g.id+'&sex='+sex+'">'+g.name+'</a>';
        }
    });
    return html+'<div class="m-cl"></div>';
}

function getPuls(select, limit = 4, cid = '', sex = ''){
    ajax('/goods/search', {cid: cid, sex: sex, random: 1, limit: limit}, function(res){
        var goods = res.data.list;
        var html = '';
        $.each(goods, function(i, g){
            html = html + '<div><a href="/goods/detail/'+g.goods_id+'"><img  src="'+g.original_img+'" alt=""><p class="goods-name m-name">'+g.goods_name+'</p><p class="goods-price">¥'+(Number(g.shop_price)/100).toFixed(2)+'</p></a></div>';
        });
        $('#'+select).html(html);
    }, 'GET', 0);
}