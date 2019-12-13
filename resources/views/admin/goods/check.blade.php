@extends('layouts.admin')
@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:void(0);">首页</a>
        <a href="javascript:void(0);">商品管理</a>
        <a>
            <cite>数据转移</cite>
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
                            <input class="layui-input" placeholder="开始价格"  name="start" id="start"  @isset($requestes['start']) value="{{ $requestes['start'] }}" @endisset></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="截止价格"  name="end" id="end"  @isset($requestes['end']) value="{{ $requestes['end'] }}" @endisset></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="sex">
                                <option value="2" @isset($requestes['sex']) @if($requestes['sex'] == 2) selected @endif @endisset>女装</option>
                                <option value="0" @isset($requestes['sex']) @if($requestes['sex'] === '0') selected @endif @endisset>不限</option>
                                <option value="1" @isset($requestes['sex']) @if($requestes['sex'] == 1) selected @endif @endisset>男装</option>
                                <option value="3" @isset($requestes['sex']) @if($requestes['sex'] == 3) selected @endif @endisset>儿童</option>
                                <option value="4" @isset($requestes['sex']) @if($requestes['sex'] == 4) selected @endif @endisset>日韩女装</option>
                                <option value="5" @isset($requestes['sex']) @if($requestes['sex'] == 5) selected @endif @endisset>男鞋</option>
                                <option value="6" @isset($requestes['sex']) @if($requestes['sex'] == 6) selected @endif @endisset>女鞋</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                    <div id="errors"></div>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger delAll" data-url="g/cd">
                        <i class="layui-icon"></i>批量删除
                    </button>
                    <button class="layui-btn layui-btn-normal mulYes">
                        <i class="layui-icon layui-icon-template-1"></i>批量确认
                    </button>
                </div>
                <div class="layui-card-body  table-over">
                    <table class="layui-table layui-form">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="" lay-skin="primary" id="checkboxall"></th>
                                <th>ID</th>
                                <th>GID</th>
                                <th>名称</th>
                                <th>价格</th>
                                <th>源地址</th>
                                <th>图片</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <tr>
                                <td><input type="checkbox" name="" lay-skin="primary" data-id="{{ $l->id }}"></td>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->gid }}</td>
                                <td><a class="c-red" href="/admin/g/cd/{{ $l->id }}">{{ $l->title }}</a></td>
                                <td>{{ $l->prices }}</td>
                                <td><a class="c-red" target="_blank" href="{{ $l->url }}">{{ $l->url }}</a></td>
                                <td><img src="{{ $l->cover[0]->thumb }}" alt=""></td>
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
layui.use(['comm', 'form', 'jquery','layer','flow'], function(){
    var form = layui.form
    ,comm = layui.comm
    ,layer = layui.layer
    ,flow = layui.flow
    ,$ = layui.jquery;
    comm.checkbox();
    flow.lazyimg({scrollElem: '.table-over'});
    $('.mulYes').on('click', function () {
        var ids = comm.checkIds();
        if(!ids) return;
        layer.open({
            type: 1,
            title: '加价比例',
            shadeClose: true,
            area: ['450px', '420px'], //宽高
            content: '<form class="layui-form mt10  pl30 pr30">'
                +'<div class="layui-input-inline layui-show-xs-block">'
                +'<select name="ratio" id="ratio">'
                    +'<option value="30" selected="">30%</option>'
                    +'<option value="40">40%</option>'
                    +'<option value="50">50%</option>'
                    +'<option value="80">80%</option>'
                    +'<option value="100">100%</option>'
                    +'<option value="150">150%</option>'
                    +'<option value="200">200%</option>'
                    +'<option value="300">300%</option>'
                    +'<option value="500">500%</option>'
                +'</select>'
                +'</div>'
                +'<div class="layui-input-inline layui-show-xs-block mt10 t-ac">'
                +'<a class="layui-btn" id="ratio-btn">确认添加</a>'
                +'</div>'
                +'</form>'
        });
        form.render('select');
        $('#ratio-btn').on('click', function () {
            var ids = comm.checkIds();
            if(!ids) return;
            comm.ajax('/admin/g/mulcheck', {ids: ids, ratio: $('#ratio').val()}, function(res){
                if(res.status !== 200){
                    comm.msg(res.msg, 2);
                }else{
                    if(res.data.length) {
                        comm.msg('有部分错误信息', 2);
                        console.log(res.data);
                        $('#errors').append('<p>'+res.data+'</p>');
                    }else{
                        comm.msg('操作成功', 1);
                        comm.winReload();
                    }
                }
            });
            return false;
        });
    });
});
</script>
@endsection