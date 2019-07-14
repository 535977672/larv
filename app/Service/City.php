<?php
namespace App\Service;

use Illuminate\Support\Facades\DB;

/**
 * 
 */
class City extends Service{

    public function getCityStr() {
        $city = DB::table('city')
            ->select('id as code', 'name')
            ->where('deleted', 0)
            ->where('pid', 0)
            ->offset(0)
            ->limit(10000)
            ->get();
        foreach ($city as $key => $value) {
            $city2 = DB::table('city')
                ->select('id as code', 'name')
                ->where('deleted', 0)
                ->where('pid', $value->code)
                ->offset(0)
                ->limit(10000)
                ->get();
            if($city2){
                foreach ($city2 as $key2 => $value2) {
                    $city3 = DB::table('city')
                        ->select('id as code', 'name')
                        ->where('deleted', 0)
                        ->where('pid', $value2->code)
                        ->offset(0)
                        ->limit(10000)
                        ->get();
                    $value2->sub = $city3;
                }
            }
            $value->sub = $city2;
        }
        return $city->toJson(JSON_UNESCAPED_UNICODE);
    }
    
    public function delCity($id) {
        $num = DB::table('city')
            ->where('id', $id)
            ->update(['deleted' => 1]);
        return $num;
    }
    
    public function reDelCity($id) {
        $num = DB::table('city')
            ->where('id', $id)
            ->update(['deleted' => 0]);
        return $num;
    }
}
