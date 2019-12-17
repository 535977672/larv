<?php

namespace App\Http\Controllers\Admin;

use App\Service\Cache;

class CacheController extends AdminController
{
    public function opt()
    {
        return $this->successful();
    }
    
    public function clean($id)
    {
        Cache::clean($id);
        return $this->successful('清除缓存成功');
    }
}
