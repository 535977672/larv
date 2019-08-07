<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Service\Goods as GoodsService;

class GoodsController extends AdminController
{
    /**
     * 收集数据检查
     * @return type
     */
    public function check()
    {
        $list = DB::select('select id,url,cover,title from tb_attr  where deleted = 0');
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
        DB::update('update tb_attr set deleted = 1 where id in (?)', [$this->request->post('ids', 0)]);
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
            $value->content = html_entity_decode($value->content, ENT_QUOTES);
            if(!empty($value->attr)){
                $value->attr = json_decode($value->attr);
            }
            if(!empty($value->price)){
                $value->price = json_decode($value->price);
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
            'cost' => $this->request->post('cost', 0),
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
            'store_count' => intval($this->request->post('store_count', 10)),
        ];
        
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
        $price_spec_name = $this->request->post('price_spec_name', '');
        $price_spec_price = $this->request->post('price_spec_price', '');
        $price_spec_count = $this->request->post('price_spec_count', '');
        $price_spec_real_price = $this->request->post('price_spec_real_price', '');
        $price_spec_real_count = $this->request->post('price_spec_real_count', '');
       
        $spec = [];
        if($price_color_thumb){
            foreach ($price_color_thumb as $k=>$color){
                if($color){
                    $temp = [];
                    $temp['price_color_thumb'] = $color;
                    $temp['price_color_preview'] = $price_color_preview[$k];
                    foreach ($price_spec_name[$k] as $s=>$p){
                        if($p){
                            $temp2 = [];
                            $temp2['price_spec_name'] = $price_spec_name[$k][$s];//name/img
                            $temp2['price_spec_price'] = intval(floatval($price_spec_price[$k][$s])*100);
                            $temp2['price_spec_count'] = intval($price_spec_count[$k][$s]);
                            $temp2['price_spec_real_price'] = intval(floatval($price_spec_real_price[$k][$s])*100);
                            $temp2['price_spec_real_count'] = intval($price_spec_real_count[$k][$s]);
                            $temp['spec'][] = $temp2;
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
                            $temp2['price_spec_count'] = intval($price_spec_count[$k][$s]);
                            $temp2['price_spec_real_price'] = intval(floatval($price_spec_real_price[$k][$s])*100);
                            $temp2['price_spec_real_count'] = intval($price_spec_real_count[$k][$s]);
                            $temp['spec'][] = $temp2;
                        }
                    }
                    $spec[] = $temp;
                }
            }
        }
        $datas['spec'] = $spec;
        $goods = new GoodsService();
        if($goods->saveGoods($datas) === false){
            return return_ajax(0, $goods->getErrorMsg());
        }
        return return_ajax(200, '保存成功');
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
}
