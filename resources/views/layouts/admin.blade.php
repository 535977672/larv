<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>admin后台管理系统</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <link rel="stylesheet" href="/static/admin/css/font.css">
    <link rel="stylesheet" href="/static/admin/css/xadmin.css">
    <link rel="stylesheet" href="/static/css/admin.css?time={{ time() }}">
    <link rel="stylesheet" href="/static/css/comm.css?time={{ time() }}">
    <!-- <link rel="stylesheet" href="/static/admin/css/theme5.css"> -->
    @yield('head')
    <!--<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>-->
    <script src="/static/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        // 是否开启刷新记忆tab功能
        // var is_remember = false;
    </script>
</head>
<body class="@yield('class')">
    @yield('content')
    
    <script>
    layui.config({
        base: '/static/modules/'
    }).use(['comm','jquery'], function(){
        var comm = layui.comm
        ,$ = layui.jquery
        ,table = layui.table;
        
        $('.delAll').on('click', function(){
            delAll($(this));
        });
        function delAll(obj){
            var data = $(".layui-form-checked").not('.header').prev('input')
            ,ids = '';
            $.each(data, function(i,v){
                ids = ids + ',' +$(v).attr('data-id');
            });
            ids = ids.substr(1);
            if(comm.isEmpty(ids)) {
                layer.msg('选择删除数据', {icon: 1});
                return;
            }
            layer.confirm('确认要删除吗？',function(index){
                comm.ajax('/admin/'+obj.attr('data-url'), {ids: ids}, function(res){
                    if(res.status !== 200){
                        layer.msg(res.msg, {icon: 2});
                    }else{
                        layer.msg('操作成功', {icon: 1});
                        $(".layui-form-checked").not('.header').parents('tr').remove();
                    }
                });
                return false;
            });
        }
    });
    </script>
    @yield('script')
</body>    
</html>