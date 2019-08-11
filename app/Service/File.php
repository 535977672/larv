<?php
namespace App\Service;

use Illuminate\Support\Facades\Redis;

/**
 * 
 */
class File extends Service{

    /**
     * storage/app/pay文件移动到storage/app/public/pay
     * $name 不含后缀
     */
    public function payFileCopy($name, $type = 1){
        $baseDir = '../storage/app/pay/';
        if($type == 1) {
            $baseDir = $baseDir . 'a/';
        } else {
            $baseDir = $baseDir . 'x/';
        }
        $fileName = $baseDir . 'pay_' . $name . '.jpg';
        $uniqid = uniqid('pay', true) . '.jpg';
        $dirName = '../storage/app/public/pay/' . $uniqid;
        
        if(!is_file($fileName)){
            //$this->setErrorMsg('文件不存在');
            //return false;
            $fileName = $baseDir . 'paycomm.jpg';
        }
        
        if(!copy( $fileName , $dirName)){
            $this->setErrorMsg('创建文件失败');
            return false;
        }
        
        $redis = Redis::connection();
        $redis->hSet('pay', $uniqid, time());
        return env('APP_URL').'/storage/pay/' . $uniqid;
    }
    
    /**
     * storage/app/public/pay文件删除
     * @param type $name 含后缀
     * @return boolean
     */
    public function payFileDel($name){
        $dirName = '../storage/app/public/pay/' . $name;
        $redis = Redis::connection();
        $redis->hDel('pay', $name);
        if(is_file($dirName)){
            return unlink($dirName);
        }
        return false;
    }
    
    /**
     * storage/app/public/pay文件删除
     * @return boolean
     */
    public function payFileCheck(){

        $redis = Redis::connection();
        $file = $redis->hGetAll('pay');
        if($file){
            foreach ($file as $k=>$v) {
                if(time()-$v > 300){
                    $this->payFileDel($k);
                }
            }
        }
        return true;
    }
}
