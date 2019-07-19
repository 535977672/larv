<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    protected $table = 'order_goods';
    protected $primaryKey = 'og_id';
    public $timestamps = false;
}
