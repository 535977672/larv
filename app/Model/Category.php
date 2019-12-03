<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'm_category';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = ['id', 'pid', 'name', 'sort', 'pic', 'is_show', 'level', 'sex'];

    public static function getOneByField($name, $value) {
        return self::where($name, $value)->first();
    }

    public static function add($data) {
        return self::create($data);
    }

    public static function addFirstOrCreate($key, $data) {
        return self::firstOrCreate($key, $data);
    }
}
