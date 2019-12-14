@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a>
            <cite>商品列表</cite>
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
                    <form class="layui-form layui-col-space5" method="get">
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="type">
                                <option value="">商品类型</option>
                                <option value="1" @isset($requestes['type']) @if($requestes['type'] == 1) selected @endif @endisset>普通商品</option>
                                <option value="2" @isset($requestes['type']) @if($requestes['type'] == 2) selected @endif @endisset>套餐商品</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="is_on_sale">
                                <option value="">是否上架</option>
                                <option value="1" @isset($requestes['is_on_sale']) @if($requestes['is_on_sale'] == 1) selected @endif @endisset>上架</option>
                                <option value="0" @isset($requestes['is_on_sale']) @if($requestes['is_on_sale'] === '0') selected @endif @endisset>下架</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="sex">
                                <option value="2" @isset($requestes['sex']) @if($requestes['sex'] == 2) selected @endif @endisset>女装</option>
                                <option value="0" @isset($requestes['sex']) @if($requestes['sex'] === '0') selected @endif @endisset>不限</option>
                                <option value="1" @isset($requestes['sex']) @if($requestes['sex'] == 1) selected @endif @endisset>男装</option>
                                <option value="2" @isset($requestes['sex']) @if($requestes['sex'] == 2) selected @endif @endisset>女装</option>
                                <option value="3" @isset($requestes['sex']) @if($requestes['sex'] == 3) selected @endif @endisset>儿童</option>
                                <option value="4" @isset($requestes['sex']) @if($requestes['sex'] == 4) selected @endif @endisset>日韩女装</option>
                                <option value="5" @isset($requestes['sex']) @if($requestes['sex'] == 5) selected @endif @endisset>男鞋</option>
                                <option value="6" @isset($requestes['sex']) @if($requestes['sex'] == 6) selected @endif @endisset>女鞋</option>
                                <option value="99" @isset($requestes['sex']) @if($requestes['sex'] == 99) selected @endif @endisset>保健</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="is_recommend">
                                <option value="">是否推荐</option>
                                <option value="1" @isset($requestes['is_recommend']) @if($requestes['is_recommend'] == 1) selected @endif @endisset>已推荐</option>
                                <option value="0" @isset($requestes['is_recommend']) @if($requestes['is_recommend'] === '0') selected @endif @endisset>未推荐</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="is_new">
                                <option value="">是否新品</option>
                                <option value="1" @isset($requestes['is_new']) @if($requestes['is_new'] == 1) selected @endif @endisset>新品</option>
                                <option value="0" @isset($requestes['is_new']) @if($requestes['is_new'] === '0') selected @endif @endisset>非新品</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="is_hot">
                                <option value="">是否热卖</option>
                                <option value="1" @isset($requestes['is_hot']) @if($requestes['is_hot'] == 1) selected @endif @endisset>热卖</option>
                                <option value="0" @isset($requestes['is_hot']) @if($requestes['is_hot'] === '0') selected @endif @endisset>非热卖</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="limit">
                                <option value="20" @isset($requestes['limit']) @if($requestes['limit'] == 20) selected @endif @endisset>20条</option>
                                <option value="50" @isset($requestes['limit']) @if($requestes['limit'] == 50) selected @endif @endisset>50条</option>
                                <option value="100" @isset($requestes['limit']) @if($requestes['limit'] == 100) selected @endif @endisset>100条</option>
                                <option value="200" @isset($requestes['limit']) @if($requestes['limit'] == 200) selected @endif @endisset>200条</option>
                                <option value="500" @isset($requestes['limit']) @if($requestes['limit'] == 500) selected @endif @endisset>500条</option>
                                <option value="1000" @isset($requestes['limit']) @if($requestes['limit'] == 1000) selected @endif @endisset>1000条</option>
                                <option value="2000" @isset($requestes['limit']) @if($requestes['limit'] == 2000) selected @endif @endisset>2000条</option>
                                <option value="5000" @isset($requestes['limit']) @if($requestes['limit'] == 5000) selected @endif @endisset>5000条</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn" onclick="xadmin.open('添加套餐','/admin/g/goodsteamtoadd', '1000', '520')">
                        <i class="layui-icon"></i>添加套餐
                    </button>
                    <button class="layui-btn layui-btn-normal isonsale" data-status="1" data-url="g/isonsale"><i class="layui-icon layui-icon-up"></i>上架</button>
                    <button class="layui-btn layui-btn-danger isonsale" data-status="0" data-url="g/isonsale"><i class="layui-icon layui-icon-down"></i>下架</button>
                </div>
                <div class="layui-card-body table-over">
                    <table class="layui-table layui-form">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="" lay-skin="primary">
                                </th>
                                <th>ID</th>
                                <th>商品</th>
                                <th>图片</th>
                                <th>价格</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" id="checkboxall" data-id="{{ $l->goods_id	 }}"></td>
                                <td>{{ $l->goods_id	 }}</td>
                                <td>
                                    @if($l->type == 1)
                                    <a class="c-red" target="_blank" href="{{ $l->ext->original_url }}">{{ $l->goods_name }}</a>
                                    @else
                                    {{ $l->goods_name }}
                                    @endif
                                </td>
                                <td><a class="c-red" target="_blank" href="{{ $l->original_img }}"><img lay-src="{{ $l->original_img }}" height="100px" width="100px"></a></td>
                                <td>本店价{{ price_format($l->shop_price) }}<br/>成本价{{ price_format($l->cost_price) }}</td>
                                <td>{{ $l->is_on_sale_str }}</td>
                                <td class="td-manage">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <div>
                            {{ $list->links() }}
                            
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
layui.use(['comm', 'form', 'layer', 'jquery','flow'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,flow = layui.flow
    ,$ = layui.jquery;
    comm.checkbox();
    flow.lazyimg({scrollElem: '.table-over'});
    $('.isonsale').on('click', function(){
        isonsale($(this));
    });
    function isonsale(obj){
        var data = $("tbody .layui-form-checked").not('.header').prev('input')
        ,ids = '';
        $.each(data, function(i,v){
            ids = ids + ',' +$(v).attr('data-id');
        });
        ids = ids.substr(1);
        if(comm.isEmpty(ids)) {
            layer.msg('选择数据', {icon: 2});
            return;
        }
        layer.confirm('确认操作？',function(index){
            comm.ajax('/admin/'+obj.attr('data-url'), {ids: ids, status: obj.attr('data-status')}, function(res){
                if(res.status !== 200){
                    layer.msg(res.msg, {icon: 2});
                }else{
                    layer.msg('操作成功', {icon: 1});
                    location.reload();
                }
            });
            return false;
        });
    }
    
});
</script>
@endsection