function isEmpty(obj){
    if(typeof obj == "undefined" || obj == null || obj == ""){
        return true;
    }else{
        return false;
    }
}
//全局登录判断
loginOut();
function isLogin(){
    return uncompileStr(sessionStorage.getItem("isLogin"));
}

function loginIn(){
    if(isLogin() !== 'isLogin') sessionStorage.setItem("isLogin", compileStr('isLogin'));
}

function loginOut(){
    sessionStorage.setItem("isLogin", compileStr('0'));
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
function ajax(url, data = {}, callback = '', type = 'POST', load = 1, cache = 0){
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
    
    data.lses = sessionStorage.getItem('lses');
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
            if(cache>0){
                var c = [];
                c.push(new Date().getTime()+3600000);
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
            $.toast(str, "cancel");
        }
    });
};

function ajaxLoading(){
    $.showLoading("数据加载中");
}

function closeAjaxLoading(){
    $.hideLoading();
}

//刷新
function winReload(par = 0){
    if(par === 0)
        location.reload();
    else
        parent.location.reload();
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
var cookie = {
    set: function(cname,cvalue,exdays){
        var d = new Date();
        d.setTime(d.getTime()+(exdays*24*60*60*1000));
        var expires = "expires="+d.toGMTString();
        document.cookie = cname+"="+cvalue+"; "+expires;
    },
    get: function(cname){
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i].trim();
            if (c.indexOf(name)==0) { return c.substring(name.length,c.length); }
        }
        return null;
    }
};

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
                        $.toast("登录成功");
                    }else{
                        $.toast(res.msg, "cancel");
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
                        $.toast("登录成功");
                    }else{
                        $.toast(res.msg, "cancel");
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
