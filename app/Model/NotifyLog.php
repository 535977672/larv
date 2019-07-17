<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NotifyLog extends Model
{
    protected $table = 'notify_log';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'content', 'pid', 'status', 'create_time'];
}
