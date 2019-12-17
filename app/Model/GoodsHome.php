<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsHome extends Model
{
    protected $table = 'm_goods_home';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected  $guarded = [];//不想要被批量赋值的属性数组

    public function menu()
    {
        return $this->hasOne('App\Model\GoodsMenu', 'menu_id', 'menu_id');
    }

    public function cate()
    {
        return $this->hasOne('App\Model\Category', 'id', 'cid');
    }

    public static function getAllGoodsByMenu($menu_id){
        return self::with(['menu','cate'])->where('menu_id', $menu_id)->get();
    }

    public static function delGoodsById($id){
        return self::destroy(is_array($id)?$id:explode(',', $id));
    }

    public static function delGoodsByMenu($menu_id){
        return self::whereIn('menu_id', is_array($menu_id)?$menu_id:explode(',', $menu_id))->delete();
    }

    public static function goodsAdd($data){
        return self::insert($data);
    }
}
