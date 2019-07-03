@extends('layouts.app')
@section('title', 'index')
@section('content')
<div class="container">
    <div class="swiper-container mt5">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img class="m_mw" src="/r/1.jpg" alt=""></div>
            <div class="swiper-slide"><img class="m_mw" src="/r/2.jpg" alt=""></div>
            <div class="swiper-slide"><img class="m_mw" src="/r/3.jpg" alt=""></div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="mt5 p5 m-goods">
        <div class="goods-price">
            <i class="price-symbol">¥</i>
            <span class="price">1480-3198</span>
            <span class="price-icon">心动价</span>
        </div>
        <div class="goods-name m-name">2332北欧羽绒乳胶沙发现代简约小户型可拆洗棉麻布艺沙发组合双三人位</div>
        <div class="goods-adds">
            <span>快递 0.00</span>
            <span>月销量 333件</span>
            <span>广东佛山</span>
        </div>
        <div class="goods-prom f-12 pt5">
            <span class="c-888 mr5">促销</span>
            <span class="c-red f-10">积分</span>
            <span>购买可得740积分</span>
        </div>
        <div class="module-sku" data-mod-name="detail-m/mods/module-sku/index">
            <div class="skuText">
                  <div class="l">选择</div>
                  <div class="r" data-spm-anchor-id="a222m.7628550.0.i2">请选择颜色分类/几人坐</div>
            </div>
                
        </div>
        <div class="mui-tagscloud-main">
            <div class="mui-tagscloud-title">
              商品评价 (18032)
                <div class="mui-tagscloud-more rule-color-main">
                  查看全部
                </div>
            </div>
            <div class="mui-tagscloud-comments">
                <div class="mui-tagscloud-user">
                    <img class="mui-tagscloud-img" src="">
                    <img class="mui-tagscloud-head-extra" src="">
                    <span class="mui-tagscloud-name">jhj</span>
                    <img class="mui-tagscloud-icon" src="">
                </div>
                <div class="mui-tagscloud-content">物流很快，师傅安装也特别快，走的时候把垃圾也带走，质量可以，简约风，不占地方，可以坐三四个人，喜欢稍微硬一点的，这款刚刚好</div>
                <div class="mui-tagscloud-date">2019-06-29 颜色分类:浅灰色-海绵款;几人坐:三人208*89cm</div>
            </div>
        </div>
        
        
    </div>
</div>

@endsection
@section('script')
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js"></script>
<script>
$(".swiper-container").swiper({
    autoplay : 3000,
    pagination : '.swiper-pagination',
    paginationClickable :true,
    autoplayDisableOnInteraction : false,
    effect : 'slide'
});
</script>
@endsection
