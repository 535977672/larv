<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

class GoodsController extends AdminController
{
    /**
     * 收集数据检查
     * @return type
     */
    public function check()
    {
        $list = DB::select('select id,url,cover,title from tb_attr');
        foreach ($list as $key => $value) {
            $value->cover = json_decode($value->cover);
        }
        return view('admin.goods.check', ['list'=>$list]);
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
        $data = $this->request->all();
        $goods_name = $data['goods_name'];
        $type = $data['type'];
        $url = $data['url'];
        $brand = $data['brand'];
        $limit = $data['limit'];
        $addr = $data['addr'];
        $cost = $data['cost'];
        $attr = $data['attr'];//array
        $is_on_sale = $data['is_on_sale'];//on off
        $is_hot = $data['is_hot'];
        $is_new = $data['is_new'];
        $is_recommend = $data['is_recommend'];
        $give_integral = $data['give_integral'];
        $sort = $data['sort'];
        $original_img = $data['original_img'];
        
        $cover_thumb = $data['cover_thumb'];
        $cover_preview = $data['cover_preview'];
        
        $price_color_thumb = $data['price_color_thumb'];
        $price_color_preview = $data['price_color_preview'];
        $price_spec_name = $data['price_spec_name'];
        $price_spec_price = $data['price_spec_price'];
        $price_spec_count = $data['price_spec_count'];
        $price_spec_real_price = $data['price_spec_real_price'];
        $price_spec_real_count = $data['price_spec_real_count'];
        
        $content = $data['content'];
        
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
                            $temp2['price_spec_price'] = $price_spec_price[$k][$s];
                            $temp2['price_spec_count'] = $price_spec_count[$k][$s];
                            $temp2['price_spec_real_price'] = $price_spec_real_price[$k][$s];
                            $temp2['price_spec_real_count'] = $price_spec_real_count[$k][$s];
                            $temp['spec'] = $temp2;
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
                            $temp2['price_spec_price'] = $price_spec_price[$k][$s];
                            $temp2['price_spec_count'] = $price_spec_count[$k][$s];
                            $temp2['price_spec_real_price'] = $price_spec_real_price[$k][$s];
                            $temp2['price_spec_real_count'] = $price_spec_real_count[$k][$s];
                            $temp['spec'] = $temp2;
                        }
                    }
                    $spec[] = $temp;
                }
            }
        }
        
        return return_ajax(0, '保存失败', $data);
    }
}
