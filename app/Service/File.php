<?php
namespace App\Service;

use Illuminate\Support\Facades\Redis;

/**
 * 短信发送
 */
class File extends Service{

    /**
     * storage/app/pay文件移动到storage/app/public/pay
     * $name 不含后缀
     */
    public function payFileCopy($name){
        $fileName = '../storage/app/pay/pay_' . $name . '.png';
        $uniqid = uniqid('pay', true) . '.png';
        $dirName = '../storage/app/public/pay/' . $uniqid;
        
        if(!is_file($fileName)){
            $this->setErrorMsg('文件不存在');
            return false; 
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
        
        if(is_file($dirName)){
            return unlink($dirName);
        }
        return false;
    }
}
