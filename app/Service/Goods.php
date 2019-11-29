<?php
namespace App\Service;

use App\Model\Goods as GoodsModel;
use App\Model\GoodsAttr;
use App\Model\GoodsColor;
use App\Model\GoodsExt;
use App\Model\GoodsComment;
use Illuminate\Support\Facades\DB;
use \Exception;

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

    public function getGoodsByName($goodsName){
        return GoodsModel::where('goods_name', $goodsName)->first();
    }
    
    public function getGoodsList($limit = 20, $where = []){
        return GoodsModel::where([
                //['store_count', '>', 0],
                ['is_on_sale', '=', 1],
            ])
            ->where($where)
            ->select(DB::raw('goods_id,goods_name,shop_price,original_img'))
            ->orderBy('goods_id', 'desc')
            ->simplePaginate($limit);
    }
    
    public function getGoodsHotList($limit = 20){
        return GoodsModel::where([
                //['store_count', '>', 0],
                ['is_on_sale', '=', 1],
                ['is_hot', '=', 1],
            ])
            ->select(DB::raw('goods_id,goods_name,shop_price,original_img'))
            ->orderBy('goods_id', 'desc')
            ->simplePaginate($limit);
    }
    
    public function getGoodsDetail($id = 0, $field = ''){
        if(!$field) $field = 'goods_id,goods_name,store_count,comment_count,shop_price,original_img,type,ids,sales_sum,addr,video';
        $select = DB::raw($field);
        $goods = GoodsModel::where('goods_id', $id)->select($select)->first();
        if(!$goods) return false;
        //普通
        if($goods->type == 1){
            $goods->load('ext');//null/coll
            //attr预加载
            //$color = GoodsColor::with(['attr' => function ($query) {$query->select('attr_id','goods_id','color_id');}])
            $colores = GoodsColor::with('attr')->where('goods_id', $id)->get();
            if($colores->isNotEmpty()){
                //$goods->color = $color;
                $color = [];
                $attr = [];
                foreach ($colores as $k => $v) {
                    $attr[] = $v->attr;
                    unset($v->attr);
                    $color[] = $v;
                }
                $goods->color = $color;
                $goods->attr = $attr;
            }else{
                //attr延迟预加载
                //$goods->load('attr:color_id,goods_id,attr_id');//array
                $goods->load('attr');
                $goods->color = [];
            }
            
        }else{
            $goods->goods = $this->getSubGoods($goods->ids);
        }
        return $goods;
    }
    
    public function getSubGoods($ids = '', $field = '') {
        if(!$ids) return null;
        $ids = json_decode($ids, true);
        if(!$ids) return null;
        if(!$field) $field = 'goods_id,goods_name,store_count,comment_count,shop_price,original_img,type,ids,sales_sum,addr';
        $select = DB::raw($field);
        return GoodsModel::whereIn('goods_id', array_keys($ids))->where('type', 1)->select($select)->get();
    }
    
    public function getSubGoodsAttr($ids) {
        if(!$ids) return null;
        $ids = json_decode($ids, true);
        if(!$ids) return null;
        return $this->getSubGoodsAttrByAttrIds(array_values($ids));
    }
    
    public function getSubGoodsAttrByAttrIds($attrids) {
        if(!$attrids) return null;
        $field = 'm_goods.goods_id,m_goods.goods_name,m_goods.original_img,m_goods.type,m_goods_attr.color_id,m_goods_attr.attr,m_goods_attr.attr_price,m_goods_attr.attr_img,m_goods_attr.attr_id,m_goods_attr.img attrimg,m_goods_color.color,m_goods_color.color_img,m_goods_color.img colorimg';
        $select = DB::raw($field);
        return GoodsAttr::whereIn('m_goods_attr.attr_id', $attrids)
                ->where('m_goods.type', 1)
                ->join('m_goods', 'm_goods_attr.goods_id', '=', 'm_goods.goods_id')
                ->leftJoin('m_goods_color', 'm_goods_attr.color_id', '=', 'm_goods_color.color_id')
                ->select($select)
                ->get();
    }
    
    public function saveGoods($data, $id = 0) {
        try {
            DB::beginTransaction();
            
            $goodsModel = new GoodsModel;
            $goodsModel->goods_name = $data['goods_name'];
            if(isset($data['brand']) && $data['brand']) $goodsModel->brand = $data['brand'];
            if(isset($data['store_count']) && $data['store_count']) $goodsModel->store_count = $data['store_count'];
            if(isset($data['market_price']) && $data['market_price']) $goodsModel->market_price = $data['market_price'];
            if(isset($data['shop_price']) && $data['shop_price']) $goodsModel->shop_price = $data['shop_price'];
            
            if(isset($data['cost_price']) && $data['cost_price']) $goodsModel->cost_price = $data['cost_price'];
            $goodsModel->original_img = $data['original_img'];
            $goodsModel->is_on_sale = $data['is_on_sale'];
            $goodsModel->sort = $data['sort'];
            $goodsModel->is_recommend = $data['is_recommend'];
            
            $goodsModel->is_new = $data['is_new'];
            $goodsModel->is_hot = $data['is_hot'];
            $goodsModel->give_integral = $data['give_integral'];
            $goodsModel->goods_type = $data['type'];
            if(isset($data['limit']) && $data['limit']) $goodsModel->limit = $data['limit'];
            
            if(isset($data['addr']) && $data['addr']) $goodsModel->addr = $data['addr'];
            if(isset($data['cost']) && $data['cost']) $goodsModel->cost = $data['cost'];
            if(isset($data['video']) && $data['video']) $goodsModel->video = $data['video'];
            if(isset($data['ex']) && $data['ex']) $goodsModel->ex = $data['ex'];

            if(isset($data['ids']) && !$data['ids']) {
                $goodsModel->ids = json_encode($data['ids']);
                $goodsModel->type = 2;
            }else{
                $goodsModel->type = 1;
            }
            if(!$id){
                $goodsModel->comment_count = 0; //intval(getRandStr(2, 3));
                $goodsModel->collect_sum = 0;//intval(getRandStr(3, 3));
                $goodsModel->sales_sum = 0;//intval(getRandStr(2, 3));
            }else{
                $goodsModel->goods_id = $id;
            }
            if($goodsModel->save() === false){
                throw new Exception('保存商品失败');
            }
            if(!isset($data['ids']) || !$data['ids']) {
                $goodsExtModel = new GoodsExt;
                $goodsExtModel->goods_id = $goodsModel->goods_id;
                $goodsExtModel->original_url = $data['url'];
                $goodsExtModel->image_url = $data['image_url'];
                $goodsExtModel->attr = $data['attr'];
                $goodsExtModel->content = $data['content'];
                if($goodsExtModel->save() === false){
                    throw new Exception('保存商品附加信息失败');
                }


                foreach ($data['spec'] as $v) {
                    if(isset($v['price_color_thumb']) && ($v['price_color_thumb'] ||  $v['price_color_alt'])){
                        $goodsColor = new GoodsColor;
                        if($id) {
                            if(!isset($v['color_id']) || !$v['color_id']) throw new Exception('商品颜色ID丢失');
                            $goodsColor->color_id = $v['color_id'];
                        }
                        $goodsColor->goods_id = $goodsModel->goods_id;
                        if(stripos($v['price_color_thumb'], 'http') === false){
                            $goodsColor->color = $v['price_color_thumb']?:$v['price_color_alt'];
                            $goodsColor->color_img = '';
                            $goodsColor->img = '';
                        }else{
                            $goodsColor->color = $v['price_color_alt'];
                            $goodsColor->color_img = $v['price_color_thumb'];
                            $goodsColor->img = $v['price_color_preview'];
                        }
                        if($goodsColor->save() === false){
                            throw new Exception('保存商品颜色信息失败');
                        }
                    }
                    if($v['spec']){
                        foreach ($v['spec'] as $k => $va) {
                            $goodsAttr = new GoodsAttr;
                            if($id) {
                                if(!isset($va['attr_id']) || !$va['attr_id']) throw new Exception('商品规格ID丢失');
                                $goodsAttr->attr_id = $va['attr_id'];
                            }
                            $goodsAttr->goods_id = $goodsModel->goods_id;
                            $goodsAttr->color_id = (isset($v['price_color_thumb']) && $v['price_color_thumb'])?$goodsColor->color_id:0;
                            $goodsAttr->realprice = $va['price_spec_price'];
                            $goodsAttr->realnum = $va['price_spec_count'];
                            $goodsAttr->attr_price = $va['price_spec_real_price'];
                            $goodsAttr->num = $va['price_spec_real_count'];
                            if(stripos($va['price_spec_name'], 'http') === false){
                                $goodsAttr->attr = $va['price_spec_name'];
                                $goodsAttr->attr_img = '';
                                $goodsAttr->img = '';
                            }else{
                                $goodsAttr->attr = $va['price_spec_alt'];
                                $goodsAttr->attr_img = $va['price_spec_name'];
                                $goodsAttr->img = $va['price_spec_name'];
                            }
                            if($goodsAttr->save() === false){
                                throw new Exception('保存商品规格信息失败');
                            }
                        }
                    }
                }
            }
            
            if(isset($data['tb_id']) && $data['tb_id']){
                DB::update('update tb_attr set deleted = 1,gid = ? where id = ?', [$goodsModel->goods_id, $data['tb_id']]);
            }
            
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollBack();
            $this->setErrorMsg($exc->getMessage());
            return false;
        }
    }
    
    /**
     * 评论列表
     * @param type $limit
     * @param type $where
     * @return type
     */
    public function getGoodsCommentList($where = [], $limit = 20){
        return GoodsComment::where($where)
            ->simplePaginate($limit);
    }
    
    
    
    
    /**amdin*******************************************************************/
    public function aGoodsList($where = [], $limit = 20){
        return GoodsModel::with('ext')->where($where)
            ->orderBy('goods_id', 'desc')
            ->paginate($limit);
    }
    
    public function aGoodsAttrList($where = [], $limit = 20){
        return GoodsAttr::with(['goods', 'color'])->where($where)
            ->orderBy('goods_id', 'desc')
            ->paginate($limit);
    }
    
    public function aGoodsTeamAdd($data){
        return GoodsModel::insert($data);
    }
    
    public function aIsOnSale($ids, $status){
        $sql = "((goods_id in ($ids))";
        $ids = explode(',', $ids);
        foreach($ids as $id){
            $sql .= ' or (ids like \'%"'.$id.'":%\')';
        }
        $sql .= ')';
        if($status == 1){
            $sql .= " and type=1";
        }
        return DB::update('update m_goods set is_on_sale = '.$status.' where '.$sql);
    }
}
