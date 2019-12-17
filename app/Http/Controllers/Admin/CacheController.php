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
            if(!$re1) return $this->failed('未完全清除缓存');
        }
        return $this->successful('清除缓存成功');
    }
}
