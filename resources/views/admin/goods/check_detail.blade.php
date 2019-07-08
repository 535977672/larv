@extends('layouts.admin')
@section('head')
<link rel="stylesheet" href="/static/admin/css/login.css">
@endsection
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a href="javascript:void(0);">数据转移</a>
        <a>
            <cite>商品详情</cite>
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
                    <div class="layui-row">
                        <div class="layui-col-md12">
                            <form class="layui-form" action="">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">商品名称</label>
                                  <div class="layui-input-block">
                                        <input type="text" name="goods_name" style="width: 80%" required  lay-verify="required" placeholder="商品名称" autocomplete="off" class="layui-input" value="{{ $list->title }}">
                                  </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">类型</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="type"  placeholder="类型" class="layui-input" value="{{ $list->type }}">
                                    </div>
                                    <div class="layui-form-mid layui-word-aux">@if ($list->type == 1) tm @elseif ($list->type == 2) 1688 @elseif ($list->type == 3) tb @endif</div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">源地址</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="url"  style="width: 80%" placeholder="源地址" class="layui-input" value="{{ $list->url }}">
                                    </div>
                                    <div class="layui-form-mid layui-word-aux"><a href="{{ $list->url }}" target="_blank">源地址</a></div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">品牌</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="brand"  placeholder="品牌" class="layui-input" value="{{ $list->brand }}">
                                    </div>
                                    <label class="layui-form-label">限制</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="limit"  placeholder="限制" class="layui-input" value="{{ $list->limit }}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">发货地址</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="addr"  placeholder="发货地址" class="layui-input" value="{{ $list->addr }}">
                                    </div>
                                    <label class="layui-form-label">邮费</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="cost"  placeholder="邮费" class="layui-input" value="{{ $list->cost }}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">属性</label>
                                    <div class="layui-col-md10">
                                        @foreach ($list->attr as $k => $attr)
                                        <div class="layui-input-inline">
                                            <input type="text" name="attr[]"  placeholder="属性" class="layui-input" value="{{ $attr }}">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr class="layui-bg-green">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">上架</label>
                                    <div class="layui-input-inline">
                                        <input type="checkbox" name="is_on_sale" lay-skin="switch" checked>
                                    </div>
                                    <label class="layui-form-label">热门</label>
                                    <div class="layui-input-inline">
                                        <input type="checkbox" name="is_hot" lay-skin="switch">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">新品</label>
                                    <div class="layui-input-inline">
                                        <input type="checkbox" name="is_new" lay-skin="switch">
                                    </div>
                                    <label class="layui-form-label">推荐</label>
                                    <div class="layui-input-inline">
                                        <input type="checkbox" name="is_recommend" lay-skin="switch">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">积分</label>
                                    <div class="layui-input-inline">
                                        <input type="number" name="give_integral"  placeholder="积分" class="layui-input" value="0">
                                    </div>
                                    <label class="layui-form-label">排序</label>
                                    <div class="layui-input-inline">
                                        <input type="number" name="sort"  placeholder="排序" class="layui-input" value="1">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">原图</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="original_img"  style="width: 80%" placeholder="原图" class="layui-input" value="{{ $list->cover[0]->preview }}">
                                    </div>
                                </div>
                                <hr class="layui-bg-green">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">封面</label>
                                    <div class="layui-col-md10">
                                        @foreach ($list->cover as $k => $cover)
                                        <div class="layui-col-md12 mt10">
                                            <label class="layui-form-label">缩略</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="cover_thumb[]"  placeholder="封面" class="layui-input" value="{{ $cover->thumb }}">
                                            </div>
                                            <label class="layui-form-label">预览</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="cover_preview[]"  placeholder="封面" class="layui-input" value="{{ $cover->preview }}">
                                            </div>
                                            <div class="layui-input-inline">
                                                <img src="{{ $cover->thumb }}" width="60" height="60" alt="" style="border: 1px #FD482C solid;">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr class="layui-bg-green">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">价格</label>
                                    <div class="layui-col-md10">
                                        @foreach ($list->price as $k => $price)
                                        <hr class="layui-bg-orange">
                                        @isset($price->preview)
                                        <div class="layui-col-md12 mt10">
                                            <label class="layui-form-label">颜色</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="price_color_thumb[]"  placeholder="颜色" class="layui-input" value="{{ $price->thumb }}">
                                            </div>
                                            <label class="layui-form-label">预览</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="price_color_preview[]"  placeholder="颜色" class="layui-input" value="{{ $price->preview }}">
                                            </div>
                                            <div class="layui-input-inline">
                                                <img src="{{ $price->thumb }}" width="60" height="60" alt="" style="border: 1px #FD482C solid;">
                                            </div>
                                        </div>
                                        @endisset
                                        @foreach ($price->sku as  $sku)
                                        <hr class="layui-bg-gray">
                                        <div class="layui-col-md12 mt10">
                                            <label class="layui-form-label">规格</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="price_spec_name[{{ $k }}][]"  placeholder="规格名" class="layui-input" value="{{ $sku->name }}">
                                            </div>
                                            <div class="layui-input-inline">
                                                <input type="text" name="price_spec_price[{{ $k }}][]" placeholder="规格价格" class="layui-input" value="{{ $sku->price }}">
                                            </div>
                                            <div class="layui-input-inline">
                                                <input type="text" name="price_spec_count[{{ $k }}][]"  placeholder="规格数量" class="layui-input" value="{{ $sku->count }}">
                                            </div>
                                        </div>
                                        <div class="layui-col-md12 mt10">
                                            <label class="layui-form-label">设置价格和数量</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="price_spec_real_price[{{ $k }}][]" placeholder="本店规格价格" class="layui-input">
                                            </div>
                                            <div class="layui-input-inline">
                                                <input type="number" name="price_spec_real_count[{{ $k }}][]"  placeholder="本店规格数量" class="layui-input">
                                            </div>
                                        </div>
                                        @endforeach
                                        @endforeach
                                    </div>
                                </div>
                                <hr class="layui-bg-green">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">详情</label>
                                    <div class="layui-input-block">
                                        <textarea id="content" name="content" style="display: none;">{{ $list->content }}</textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-submit lay-filter="form" data-url="/admin/g/s">立即提交</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
layui.use(['comm', 'form','layedit','layer'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layedit = layui.layedit
    ,layer = layui.layer;
    
    var content = layedit.build('content', {
        height: 3000 //设置编辑器高度
    });
    //layedit.getContent(content);
    
    //监听提交
    form.on('submit(form)', function(data){
        console.log(data.field);
        comm.ajax(data.elem.dataset.url, data.field, function(res){
            if(res.status !== 200){
                layer.msg(res.msg, {icon: 2});
            }else{
                window.history.go(-1);
            }
        });
        return false;
    });
});
</script>
@endsection