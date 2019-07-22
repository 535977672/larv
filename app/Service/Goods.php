<?php
namespace App\Service;

use App\Model\Goods as GoodsModel;
use App\Model\GoodsAttr;
use App\Model\GoodsColor;
use App\Model\GoodsExt;
use Illuminate\Support\Facades\DB;
/**
 * 
 */
class Goods extends Service{

    public function getGoods($goodsId){
        return GoodsModel::find($goodsId);
    }
    
    public function getGoodsAttr($attrId){
        return GoodsAttr::find($attrId);
    }
    
    public function getGoodsColor($colorId){
        return GoodsColor::find($colorId);
    }
    
    public function getGoodsExt($goodsId){
        return GoodsExt::find($goodsId);
    }
    
    public function getGoodsList($limit = 20){
        return GoodsModel::where([
                ['store_count', '>', 0],
                ['is_on_sale', '=', 1],
            ])
            ->select(DB::raw('goods_id,goods_name,shop_price,original_img'))
            ->simplePaginate($limit);
    }
    
    public function getGoodsDetail($id = 0){
        $select = DB::raw('goods_id,goods_name,store_count,comment_count,shop_price,original_img,type,ids');
        $goods = GoodsModel::where('goods_id', $id)->select($select)->first();
        //普通
        if($goods->type == 1){
            $goods->load('ext');//null/coll
            //attr预加载
            //$color = GoodsColor::with(['attr' => function ($query) {$query->select('attr_id','goods_id','color_id');}])
            $color = GoodsColor::with('attr')->where('goods_id', $id)->get();
            if($color->isNotEmpty()){
                $goods->color = $color;
            }else{
                //attr延迟预加载
                //$goods->load('attr:color_id,goods_id,attr_id');//array
                $goods->load('attr');
            }
        }else{
            $goods->goods = GoodsModel::whereIn('goods_id', explode(',', $goods->ids))->where('type', 1)->select($select)->get();
        }
        return $goods;
    }
    
}
