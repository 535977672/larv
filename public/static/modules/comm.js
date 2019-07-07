layui.define(['layer', 'jquery'], function(exports){
    var layer = layui.layer
    ,$ = layui.jquery;

    //ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var obj = {};
    obj.isEmpty = function (obj){
        if(typeof obj == "undefined" || obj == null || obj == ""){
            return true;
        }else{
            return false;
        }
    };

    //ajax
    obj.loading = false;
    obj.ajax = function (url, data = {}, callback = '', type = 'POST', load = 1){
        if(obj.loading) return;
        obj.loading = true;
        if(load === 1) var index = obj.ajaxLoading();
        if(!!!type) type = 'POST';

        $.ajax({
            url: url,
            dataType: 'json',
            data: data,
            timeout: 30000,
            type: type,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(load === 1) obj.closeAjaxLoading(index);
                obj.loading = false;
                if(callback){
                    callback(res);
                }
            },
            error: function(xml){
                if(load === 1) obj.closeAjaxLoading(index);
                obj.loading = false;
                console.log(xml);
                var str = '网络错误';
                layer.msg(str, {icon: 2});
            }
        });
    };

    obj.ajaxLoading = function (){
        return layer.load();
    };

    obj.closeAjaxLoading = function (index){
        layer.close(index);
    };

    //刷新
    obj.winReload = function (par = 0){
        if(par === 0)
            location.reload();
        else
            parent.location.reload();
    };

    //登录
    obj.login = function (data) { 
        obj.ajax('/admin/login', data, function(res){
            if(res.status === 200){
                obj.winReload();
            }else{
                layer.msg(res.msg, {icon: 2});
            }
        });
    };

    //退出登录
    obj.logout = function () {
        obj.ajax('/admin/logout', {}, function(res){
            obj.winReload();
        });
    };

    exports('comm', obj);
});  