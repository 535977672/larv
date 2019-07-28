<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsExt extends Model
{
    protected $table = 'm_goods_ext';
    protected $primaryKey = 'goods_id';
    public $timestamps = false;
    
    public function getImageUrlAttribute($value)
    {
        return json_decode($value, true);
    }
    public function setImageUrlAttribute($value)
    {
        return $this->attributes['image_url'] = json_encode($value);
    }
    
    public function getAttrAttribute($value)
    {
        return json_decode($value, true);
    }
    
    public function setAttrAttribute($value)
    {
        return $this->attributes['attr'] = json_encode($value);
    }
    
    public function getContentAttribute($value)
    {
        return htmlspecialchars_decode($value);
    }
    
    public function setContentAttribute($value)
    {
        return $this->attributes['content'] = htmlspecialchars($value);
    }
}
