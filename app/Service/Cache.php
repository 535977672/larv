<?php
namespace App\Service;

use Illuminate\Support\Facades\Cache as CacheFace;

class Cache extends Service{

    /**
     * @param $type
     * @return bool
     */
    public static function clean($type)
    {
        if ($type == 1 || $type == 99) {
            $re1 = CacheFace::store('redis')->tags('main')->flush();
        }
        return true;
    }
}
