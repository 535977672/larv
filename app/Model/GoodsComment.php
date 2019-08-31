<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsComment extends Model
{
    protected $table = 'm_goods_comment';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function getImgAttribute($value)
    {
        return json_decode($value, true);
    }
}
