<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsMenu extends Model
{
    protected $table = 'm_goods_menu';
    protected $primaryKey = 'menu_id';
    public $timestamps = false;
    protected $fillable = ['menu_id', 'name', 'bg', 'type', 'limit', 'sort', 'single'];
    
    public function home()
    {
        return $this->hasMany('App\Model\GoodsHome', 'menu_id', 'menu_id');
    }

    public static function getGoodsByType($type){
        $menu = self::where('type', $type)->select('menu_id','name','bg','limit','single')->orderBy('sort', 'asc')->get();
        foreach ($menu as $item){
            $item->load(['home' => function($query) use ($item){
                $query->limit($item->limit)->inRandomOrder();
            }]);
            unset($item->limit);
        }
        return $menu;
    }

    public static function menuAdd($data){
        return self::create($data);
    }

    public static function getAllMenu(){
        return self::orderBy('type', 'asc')->orderBy('sort', 'asc')->get();
    }

    public static function delMenuById($id){
        $id = is_array($id)?$id:explode(',', $id);
        GoodsHome::delGoodsByMenu($id);
        return self::destroy($id);
    }
}
