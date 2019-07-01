
//全局登录判断
loginOut();
function isLogin(){
    return sessionStorage.getItem("isLogin");
}

function loginIn(){
    if(isLogin() === '0') sessionStorage.setItem("isLogin", 1);
}

function loginOut(){
    sessionStorage.setItem("isLogin", 0);
}

//ajax
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var loading = false;
function ajax(url, data = {}, callback = '', type = 'POST', load = 1){
    if(loading) return;
    loading = true;
    if(load === 1) ajaxLoading();
    if(!!!type) type = 'POST';
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
                            +'<input class="weui-input" type="text" id="login-name" placeholder="账号">'
                            +'</div>'
                        +'</div>'
                        +'<div class="weui-cell">'
                            +'<div class="weui-cell__bd">'
                            +'<input class="weui-input" type="password" id="login-pwd" placeholder="密码">'
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
                            +'<input class="weui-input" type="text" id="login-name" placeholder="账号 数字或字母6-18位">'
                            +'</div>'
                        +'</div>'
                        +'<div class="weui-cell">'
                            +'<div class="weui-cell__bd">'
                            +'<input class="weui-input" type="password" id="login-pwd" placeholder="密码 数字或字母6-18位">'
                            +'</div>'
                        +'</div>'
                        +'<div class="weui-cell">'
                            +'<div class="weui-cell__bd">'
                            +'<input class="weui-input" type="password" id="password_confirmation" placeholder="重复密码">'
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
                var name = $('#login-name').val(), pwd = $('#login-pwd').val(), repwd = $('#password_confirmation').val();
                ajax('/register', {name: name, password: pwd, password_confirmation: repwd}, function(res){
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
}

//退出登录
function logout() {
    loginOut();
    ajax('/logout', {}, function(res){
        winReload(1);
    });
}
