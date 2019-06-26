
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
            $.toast("网络错误", "cancel");
        }
    });
};

function ajaxLoading(){
    $.showLoading("数据加载中");
}

function closeAjaxLoading(){
    $.hideLoading();
}

function lazyload(obj){
    $(obj).lazyload({
    placeholder : "/static/img/bg.jpg", //占位
    effect: "fadeIn",// effect(特效),值有show(直接显示),fadeIn(淡入),slideDown(下拉)等,常用fadeIn
    threshold: 150, // 提前150px开始加载
    });
}