<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SexCate extends Model
{
    protected $table = 'm_sex_cate';
    protected $primaryKey = 'sc_id';
    public $timestamps = false;
    
    protected $fillable = ['sc_id', 'cid', 'sex'];

    public function cate()
    {
        return $this->hasOne('App\Model\Category', 'id', 'cid');
    }

    public static function addFirstOrCreate($key, $data) {
        return self::firstOrCreate($key, $data);
    }
}
