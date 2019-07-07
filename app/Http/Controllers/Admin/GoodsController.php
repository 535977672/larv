<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

class GoodsController extends AdminController
{
    public function check()
    {
        $list = DB::select('select id,url,cover,title from tb_attr');
        foreach ($list as $key => $value) {
            $value->cover = json_decode($value->cover);
        }
        return view('admin.goods.check', ['list'=>$list]);
    }
    
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
    
    
    
    public function save($id = 0)
    {
        $data = $this->request->all();
        
        
        return return_ajax(0, '保存失败', $data);
    }
}
