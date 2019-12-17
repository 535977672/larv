<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Service\Goods as GoodsService;
use App\Model\Category;
use App\Model\SexCate;
use App\Model\GoodsHome;
use App\Model\GoodsMenu;
use App\Model\Goods as GoodsModel;
use App\Service\Cache as CacheService;

class GoodsController extends AdminController
{
    private $goodsService = null;

    protected function initialize() 
    {
        $this->goodsService = new GoodsService;
    }

    /**
     * 收集数据检查
     * @return type
     */
    public function check()
    {
        $data = $this->request->all();
        $where = [];
        isset($data['start']) && $data['start'] >= 0 &&  $where[] = ['prices', '>=', $data['start']];
        isset($data['end']) && $data['end'] >= 0 &&  $where[] = ['prices', '<=', $data['end']];
        if(!(isset($data['sex']) && $data['sex'] >= 0)) $data['sex'] = 2;
        $where[] = ['sex', '=', $data['sex']];
        //$list = DB::select('select id,url,cover,title from tb_attr  where deleted = 0');
        $list = DB::table('tb_attr')->where('deleted', '=', 0)->where($where)->select('id', 'gid', 'url', 'cover', 'title', 'prices')->paginate(100);
        foreach ($list as $key => $value) {
            $value->cover = json_decode($value->cover);
        }
        return view('admin.goods.check', ['list'=>$list]);
    }
    
    /**
     * 删除数据检查
     * @return type
     */
    public function checkDel()
    {
        DB::table('tb_attr')->where('deleted', 0)->whereIn('id', explode(',', $this->request->post('ids', 0)))->update(['deleted' => 1]);
        return return_ajax(200, '保存成功');
    }
    
    /**
     * 数据详情
     * @param type $id
     * @return type
     */
    public function checkDetail($id = 0)
    {
        $list = DB::select('select * from tb_attr where id = :id', ['id' => $id]);
        foreach ($list as $key => $value) {
            $value->cover = json_decode($value->cover);
            if($value->type == 4 && $value->video && count($value->cover)>1) array_pop($value->cover);
            $value->content = html_entity_decode($value->content, ENT_QUOTES);
            if(!empty($value->attr)){
                $value->attr = json_decode($value->attr, true);
                foreach ($value->attr as $k => $v) {
                    if(in_array(mb_substr($v, 0, 2), ['主要','生产','保质','产地','保修','有可', ':&','主图'])){
                        unset($value->attr[$k]);
                    }else if(mb_substr($v, 0, 4) == '品牌:&' && !$value->brand){
                        $value->brand = mb_substr($v, 9);
                    }
                }
            }
            if(!empty($value->price)){
                $value->price = json_decode($value->price);
            }
            $value->video = str_replace(array("\n","\r","\r\n"), '', $value->video);
            if(!empty($value->video)){
                $value->ex = substr($value->video, strrpos($value->video, '.'));
            }
        }
        return view('admin.goods.check_detail', ['list'=>$list[0]]);
    }
    
    
    /**
     * 数据保存
     * @param type $id
     * @return type
     */
    public function save($id = 0)
    {
        if (true !== $validator = $this->validateAddGoods($this->request->all())) {
            return $validator;
        }
        
        $data = $this->request->all();
        
        $datas = [
            'tb_id' => $this->request->post('tb_id', 0),
            'goods_name' => $this->request->post('goods_name', ''),
            'type' => $this->request->post('type', 0),
            'url' => $this->request->post('url', ''),
            'brand' => $this->request->post('brand', ''),
            'limit' => $this->request->post('limit', 0),
            'addr' => $this->request->post('addr', ''),
            'cost' => intval(floatval($this->request->post('cost', 0))*100),
            'is_on_sale' => $this->request->post('is_on_sale', '')?1:0,
            'is_hot' => $this->request->post('is_hot', '')?1:0,
            'is_new' => $this->request->post('is_new', '')?1:0,
            'is_recommend' => $this->request->post('is_recommend', '')?1:0,
            'give_integral' => $this->request->post('give_integral',0),
            'sort' => $this->request->post('sort', 0),
            'original_img' => $this->request->post('original_img', ''),
            'content' => $this->request->post('content', ''),
            'shop_price' => intval(floatval($this->request->post('shop_price', 10000))*100),
            'cost_price' => intval(floatval($this->request->post('cost_price', 0))*100),
            'store_count' => 0,
            'video' => $this->request->post('video', ''),
            'ex' => $this->request->post('ex', '')
        ];
        $cate = $this->request->post('cate', '');
        $sex = $this->request->post('sex', 0);
        if($this->goodsService->getGoodsByUrl($datas['original_img'])){
            return return_ajax(0, '商品链接重复');
        }

        $attrs = $this->request->post('attr', []);
        $attr = [];
        if($attrs){
            foreach($attrs as $k=>$v){
                if($v){
                    $attr[] =$v;
                }
            }
        }
        $datas['attr'] = $attr;
        
        $cover_thumb = $this->request->post('cover_thumb', []);
        $cover_preview = $this->request->post('cover_preview', []);
        
        $image_url = [];
        if($cover_thumb){
            foreach($cover_thumb as $k=>$v){
                if($v && $cover_preview[$k]){
                    $image_url[] = [
                        'thumb' => $v,
                        'preview' => $cover_preview[$k]
                    ];
                }
            }
        }
        $datas['image_url'] = $image_url;
        
        $price_color_thumb = $this->request->post('price_color_thumb', '');
        $price_color_preview = $this->request->post('price_color_preview', '');
        $price_color_alt = $this->request->post('price_color_alt', '');
        $price_spec_name = $this->request->post('price_spec_name', '');
        $price_spec_alt = $this->request->post('price_spec_alt', '');
        $price_spec_price = $this->request->post('price_spec_price', '');
        $price_spec_count = $this->request->post('price_spec_count', '');
        $price_spec_real_price = $this->request->post('price_spec_real_price', '');
        $price_spec_real_count = $this->request->post('price_spec_real_count', '');
        $spec = [];
        if($price_color_thumb){
            foreach ($price_color_thumb as $k=>$color){
                if($color || $price_color_alt[$k]){
                    $temp = [];
                    $temp['price_color_thumb'] = $color;
                    $temp['price_color_preview'] = $price_color_preview[$k];
                    $temp['price_color_alt'] = $price_color_alt[$k];
                    foreach ($price_spec_name[$k] as $s=>$p){
                        if($p){
                            $temp2 = [];
                            $temp2['price_spec_name'] = $price_spec_name[$k][$s];//name/img
                            $temp2['price_spec_price'] = intval(floatval($price_spec_price[$k][$s])*100);
                            $temp2['price_spec_alt'] = $price_spec_alt[$k][$s];
                            $temp2['price_spec_count'] = intval($price_spec_count[$k][$s]);
                            $temp2['price_spec_real_price'] = intval(floatval($price_spec_real_price[$k][$s])*100);
                            $temp2['price_spec_real_count'] = intval($price_spec_real_count[$k][$s]);
                            $temp['spec'][] = $temp2;
                            $datas['store_count'] = $datas['store_count']+$temp2['price_spec_count'];
                        }
                    }
                    $spec[] = $temp;
                }
            }
            
        }else{
            foreach ($price_spec_name as $k=>$color){
                if($color){
                    $temp = [];
                    foreach ($price_spec_name[$k] as $s=>$p){
                        if($p){
                            $temp2 = [];
                            $temp2['price_spec_name'] = $price_spec_name[$k][$s];//name/img
                            $temp2['price_spec_price'] = intval(floatval($price_spec_price[$k][$s])*100);
                            $temp2['price_spec_alt'] = $price_spec_alt[$k][$s];
                            $temp2['price_spec_count'] = intval($price_spec_count[$k][$s]);
                            $temp2['price_spec_real_price'] = intval(floatval($price_spec_real_price[$k][$s])*100);
                            $temp2['price_spec_real_count'] = intval($price_spec_real_count[$k][$s]);
                            $temp['spec'][] = $temp2;
                            $datas['store_count'] = $datas['store_count']+$temp2['price_spec_count'];
                        }
                    }
                    $spec[] = $temp;
                }
            }
        }
        $datas['spec'] = $spec;
        $datas['sex'] = $sex;
        if($cate){
            $cated = Category::addFirstOrCreate(['name' => $cate], ['pid' => 0, 'sort' => 0, 'pic' => '', 'is_show' => 0, 'level' => 1]);
            $datas['cid'] = $cated->id;
            if($sex){
                SexCate::addFirstOrCreate(['sex' => $sex, 'cid' => $cated->id], []);
            }
        }
        $goods = new GoodsService();
        if($goods->saveGoods($datas) === false){
            return return_ajax(0, $goods->getErrorMsg());
        }
        return return_ajax(200, '保存成功');
    }

    /**
     * 批量数据检查
     * @return type
     */
    public function mulCheck()
    {
        $ids = $this->request->post('ids', '');
        $ratio = FI($this->request->post('ratio', 30));
        $ratio = $ratio<20?120:($ratio+100);
        $ratio = bcdiv($ratio, 100, 2);
        if(!$ids) {
            return return_ajax(0, '请选择数据');
        }
        $list = DB::table('tb_attr')->where('deleted', '=', 0)->whereIn('id', explode(',', $ids))->get();
        $err = [];
        foreach ($list as $item) {
            $re = $this->goodsService->mulCheckEach($item, $ratio);
            if($re !== true){
                $err[] = $re;
            }
        }
        return return_ajax(0, '保存成功', $err);
    }

    public function mulCheck2()
    {
        $ids = $this->request->post('ids', '');
        $ratio = FI($this->request->post('ratio', 30));
        $ratio = $ratio<20?120:($ratio+100);
        $ratio = bcdiv($ratio, 100, 2);
        if(!$ids) {
            return return_ajax(0, '请选择数据');
        }
        $list = DB::table('tb_attr')->where('deleted', '=', 0)->whereIn('id', explode(',', $ids))->get();
        $err = [];
        foreach ($list as $item) {
            $item->goods_name = $item->title;
            if($this->goodsService->getGoodsByUrl($item->url)){
                $err[] = $item->id . '商品链接重复';
                DB::update('update tb_attr set deleted = 1 where id = ?', [$item->id]);
                continue;
            }
            if(!empty($item->price)){
                $item->price = json_decode($item->price);
            }else{
                $err[] = $item->id . '价格不存在';
                DB::update('update tb_attr set deleted = 1 where id = ?', [$item->id]);
                continue;
            }
            if(empty($item->price[0]->sku)){
                $err[] = $item->id . '价格不存在';
                DB::update('update tb_attr set deleted = 1 where id = ?', [$item->id]);
                continue;
            }
            $item->cover = json_decode($item->cover, true);
            if($item->type == 4 && $item->video && count($item->cover)>1) array_pop($item->cover);
            $item->content = html_entity_decode($item->content, ENT_QUOTES);
            if(!empty($item->attr)){
                $item->attr = json_decode($item->attr, true);
                foreach ($item->attr as $k => $v) {
                    if(in_array(mb_substr($v, 0, 2), ['主要','生产','保质','产地','保修','有可', ':&','主图'])){
                        unset($item->attr[$k]);
                    }else if(mb_substr($v, 0, 4) == '品牌:&' && !$item->brand){
                        $item->brand = mb_substr($v, 9);
                    }
                }
            }
            $item->video = str_replace(array("\n","\r","\r\n"), '', $item->video);
            if(!empty($item->video)){
                $item->ex = substr($item->video, strrpos($item->video, '.'));
            }
            $item->cost = (is_float($item->cost) || is_numeric($item->cost)) ? bcmul($item->cost, 100) : 500;
            $item->cost_price = intval(floatval($item->price[0]->sku[0]->price)*100);
            $item->shop_price = bcmul($item->cost_price+$item->cost, $ratio);
            $datas = [
                'tb_id' => $item->id,
                'goods_name' => $item->goods_name,
                'type' => $item->type,
                'url' => $item->url,
                'brand' => $item->brand,
                'limit' => $item->limit,
                'addr' => $item->addr,
                'cost' => $item->cost,
                'is_on_sale' => 0,
                'is_hot' => $item->is_hot,
                'is_new' => $item->is_new,
                'is_recommend' => $item->is_recommend,
                'give_integral' => 0,
                'sort' => 0,
                'original_img' => $item->cover[0]['preview'],
                'content' => $item->content,
                'shop_price' => $item->shop_price,
                'cost_price' => $item->cost_price,
                'store_count' => 0,
                'video' => $item->video,
                'ex' => isset($item->ex)?$item->ex:'',
                'attr' => $item->attr,
                'sex' => $item->sex,
                'cate' => $item->cate,
                'image_url' => $item->cover
            ];

            $spec = [];
            foreach ($item->price as $k=>$color) {
                $temp = [];
                if ((isset($color->thumb) && $color->thumb) || (isset($color->alt) && $color->alt)) {
                    $temp['price_color_thumb'] = isset($color->thumb)?$color->thumb:'';
                    $temp['price_color_preview'] = isset($color->preview)?$color->preview:'';
                    $temp['price_color_alt'] = $color->alt;
                }
                foreach ($color->sku as $s => $p) {
                    if ($p) {
                        $temp2 = [];
                        $temp2['price_spec_name'] = $p->name;//name/img
                        $temp2['price_spec_price'] = intval(floatval($p->price) * 100);
                        $temp2['price_spec_alt'] = $p->alt;
                        $temp2['price_spec_count'] = intval($p->count);
                        $t = intval(floatval($p->price) * 100)+$item->cost;
                        $temp2['price_spec_real_price'] = bcmul($t, $ratio);
                        $temp2['price_spec_real_count'] = intval($p->count);
                        $temp['spec'][] = $temp2;
                        $datas['store_count'] = $datas['store_count'] + $temp2['price_spec_count'];
                    }
                }
                $spec[] = $temp;
            }
            $datas['spec'] = $spec;
            if($item->cate){
                $cated = Category::addFirstOrCreate(['name' => $item->cate], ['pid' => 0, 'sort' => 0, 'pic' => '', 'is_show' => 0, 'level' => 1]);
                $datas['cid'] = $cated->id;
                if($item->sex){
                    SexCate::addFirstOrCreate(['sex' => $item->sex, 'cid' => $cated->id], []);
                }
            }
            $goods = new GoodsService();
            if($goods->saveGoods($datas) === false){
                $err[] = $item->id . $goods->getErrorMsg();
            }
        }
        return return_ajax(0, '保存成功', $err);
    }



    /**
     * 数据过期检查
     * @return type
     */
    public function checkUrl()
    {
        $l = GoodsModel::with(['ext' => function ($query) {
            $query->select('original_url', 'goods_id');
        }])
        ->where('goods_type', 4)
        ->where('is_on_sale', 1)
        ->select('goods_id')
        ->get();
        $list = [];
        foreach ($l as $k){
            $list[$k->goods_id] = substr($k->ext->original_url, strrpos($k->ext->original_url, '-')+1, -5);
        }
        return $this->successful(['list'=>json_encode($list)]);
    }
    
    /**
     * 验证goods参数
     * @param type $data
     * @return type
     */
    protected function validateAddGoods($data)
    {
        $validator =  Validator::make($data, [
            'tb_id' =>  "required",
            'goods_name' => "required",
            'type' => "required|integer",
            'url' => "required",
            'limit' => "required",
            'addr' => "required",
            'original_img' => "required",
            'content' => "required",
            'shop_price' =>  "required",
            'cost_price' =>  "required",
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return return_ajax(0, $errors[0]);
        }
        return true;
    }

    /**
     * 验证menu参数
     * @param type $data
     * @return type
     */
    protected function validateAddMenu($data)
    {
        $validator =  Validator::make($data, [
            'name' =>  "required",
            'type' => "required",
            'limit' => "required|int",
            'sort' => "required|int",
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return return_ajax(0, $errors[0]);
        }
        return true;
    }


    /**************************************************************************/
    public function goodsList()
    {
        $data = $this->request->all();
        $limit = FI($this->request->get('limit', 20));
        $where = [];
        isset($data['is_on_sale']) && $data['is_on_sale'] >= 0 &&  $where[] = ['is_on_sale', '=', $data['is_on_sale']];
        //isset($data['sex']) && $data['sex'] >= 0 &&  $where[] = ['sex', '=', $data['sex']];
        if(!(isset($data['sex']) && $data['sex'] >= 0)) $data['sex'] = 2;
        $where[] = ['sex', '=', $data['sex']];
        isset($data['is_new']) && $data['is_new'] >= 0 &&  $where[] = ['is_new', '=', $data['is_new']];
        isset($data['is_hot']) && $data['is_hot'] >= 0 &&  $where[] = ['is_hot', '=', $data['is_hot']];
        isset($data['type']) && $data['type'] >= 1 &&  $where[] = ['type', '=', $data['type']];
        return $this->successful(['list' => $this->goodsService->aGoodsList($where, $limit), 'menu'=>GoodsMenu::getAllMenu()]);
    }
    
    public function goodsTeamToAdd()
    {
        return $this->successful(['list' => $this->goodsService->aGoodsAttrList([], 1000)]);
    }
    
    public function goodsTeamAdd()
    {
        $data = $this->request->all();
        $id = $data['ids'];
        if(!$id || !$data['goods_name'] || !$data['original_img']) return $this->failed();
        $ids = [];
        foreach($id as $v){
            if($v){
                $t = explode('-', $v);
                $ids[$t[0]] = $t[1];
            }
        }
        $data['ids'] = json_encode($ids);
        $data['original_img'] = substr($data['original_img'], 0, 4) == 'http' ? $data['original_img'] : '/storage/goods/' . $data['original_img'];
        $data['is_on_sale'] = 0;
        if(!$this->goodsService->aGoodsTeamAdd($data)) return $this->failed();
        return $this->successful();
    }
    
    public function isOnSale()
    {
        $data = $this->request->all();
        if(!$data['ids']) return $this->failed();
        if(!$this->goodsService->aIsOnSale($data['ids'], $data['status'])) return $this->failed();
        return $this->successful();
    }

    public function menu()
    {
        return $this->successful(['list' => GoodsMenu::getAllMenu()]);
    }

    public function menuToAdd()
    {
        return $this->successful();
    }

    public function menuAdd()
    {
        $data = $this->request->all();
        if (true !== $validator = $this->validateAddMenu($data)) {
            return $validator;
        }
        if(!$data['bg']) $data['bg'] = '';
        if(!GoodsMenu::menuAdd($data)) return $this->failed();
        return $this->successful();
    }

    public function menuDel()
    {
        $data = $this->request->all();
        if(!GoodsMenu::delMenuById($data['ids'])) return $this->failed();
        CacheService::clean(1);
        return $this->successful();
    }

    public function menuGoods($id)
    {
        return $this->successful(['list' => GoodsHome::getAllGoodsByMenu($id)]);
    }

    public function menuGoodsAdd()
    {
        $data = $this->request->all();
        if(!$data['ids'] || !$data['menu_id']) return $this->failed(’参数错误);
        $list = GoodsModel::whereIn('goods_id', is_array($data['ids'])?$data['ids']:explode(',', $data['ids']))
            ->select('goods_id', 'goods_name', 'shop_price', 'original_img', 'sex', 'cid')->get();
        if($list->isEmpty()) return $this->failed(没有找到数据);
        foreach($list as $l){
            $l->menu_id = $data['menu_id'];
            $l->single = $data['single'];
        }
        if(!GoodsHome::goodsAdd($list->toArray())) return $this->failed('保存失败');
        CacheService::clean(1);
        return $this->successful();
    }

    public function menuGoodsDel()
    {
        $data = $this->request->all();
        if(!GoodsHome::delGoodsById($data['ids'])) return $this->failed();
        CacheService::clean(1);
        return $this->successful();
    }
}
