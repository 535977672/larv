<?php
namespace App\Service;

use App\Model\Goods as GoodsModel;
use App\Model\GoodsAttr;
use App\Model\GoodsColor;
use App\Model\GoodsExt;
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
    
}
