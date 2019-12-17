<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Cache;

class CacheController extends AdminController
{
    public function opt()
    {
        return $this->successful();
    }
    
    public function clean($id)
    {
        if($id == 1){
            $re1 = Cache::store('redis')->tags('main')->flush();
        }
        return $this->successful('清除缓存成功');
    }
}
