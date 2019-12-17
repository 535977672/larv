@extends('layouts.admin')
@section('head')
<style>
    .bg{background-color: #c3c2c2;}
</style>
@endsection
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a href="javascript:void(0);">商品类目</a>
        <a>
            <cite>添加类目</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">类目名</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="类目名" class="layui-input">
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">背景</label>
                                <div class="layui-input-inline">
                                    <div id="bg1"></div>
                                    <input type="text" id="bg2" name="bg" autocomplete="off" placeholder="背景" class="layui-input" value="">
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">类型</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="type" lay-verify="required" autocomplete="off" placeholder="类型 1001" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">显示数量</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="limit" lay-verify="required" autocomplete="off" placeholder="显示数量" class="layui-input" value="4">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">排序</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="sort" lay-verify="required" autocomplete="off" placeholder="排序" class="layui-input" value="0">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">链接类型</label>
                                <div class="layui-input-inline">
                                    <select name="single" lay-verify="required">
                                        <option value="0" selected="">单商品</option>
                                        <option value="1" >分类</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <a href="javascript:;" class="layui-btn" lay-submit="" lay-filter="add" data-url="/admin/g/menuadd">立即提交</a>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
layui.use(['comm', 'form', 'layer', 'jquery', 'colorpicker'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,$ = layui.jquery
    ,colorpicker = layui.colorpicker;//颜色选择器

    //渲染
    colorpicker.render({
        elem: '#bg1'  //绑定元素
        ,predefine: true
        ,colors: ['#f757f2;','#9bbaf7','#e64340','rgb(237, 246, 247)','rgb(255, 255, 255)']
        ,change: function(color){
            $('#bg2').val(color);
        }
    });

    //监听提交
    form.on('submit(add)', function(data){
        comm.ajax(data.elem.dataset.url, data.field, function(res){
            if(res.status !== 200){
                layer.msg(res.msg, {icon: 2});
            }else{
                window.location.reload();
            }
        });
        return false;
    });
   
    
});
</script>
@endsection