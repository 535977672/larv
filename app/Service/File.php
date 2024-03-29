<?php
namespace App\Service;

use Illuminate\Support\Facades\Redis;
use Image;
//use Intervention\Image\ImageManagerStatic as Image;

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
        
        //$redis = Redis::connection();
        //$redis->hSet('pay', $uniqid, time());
        return env('APP_URL').'/storage/pay/' . $uniqid;
    }
    
    /**
     * storage/app/public/pay文件删除
     * @param type $name 含后缀
     * @return boolean
     */
    public function payFileDel($name){
        $dirName = storage_path('app/public/pay/') . $name;
        //$redis = Redis::connection();
        //$redis->hDel('pay', $name);
        if(is_file($dirName)){
            return unlink($dirName);
        }
        return false;
    }
    
    public function payFileDelUrl($url){
        ///storage/pay/pay5d7271f0464b88.57694164.jpg
        return $this->payFileDel(substr($url, 13));
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
    
    /**
     * 水印二维码
     * storage/app/pay文件移动到storage/app/public/pay
     * $name 不含后缀
     */
    public function payFileWaterMark($name, $exp, $type = 1) {
        $baseDir = storage_path('app/pay/');
        if($type == 1) {
            $baseDir = $baseDir . 'a/';
        } else {
            $baseDir = $baseDir . 'x/';
        }
        $fileName = $baseDir . 'pay_' . $name . '.jpg';
        $uniqid = uniqid('pay', true) . '.jpg';
        $dirName = storage_path('app/public/pay/') . $uniqid;
        if(is_file($dirName)){
            return env('APP_URL').'/storage/pay/' . $uniqid;
        }
        if(!is_file($fileName)){
            //$this->setErrorMsg('文件不存在');
            //return false;
            $fileName = $baseDir . 'paycomm.jpg';
        }
        Image::configure(array('driver' => strtoupper(substr(PHP_OS,0,3))==='WIN'?'gd':'imagick'));
        $img = Image::make($fileName);
        $w = $img->width();
        $h = $img->height();
        $color = $type == 1?'#2275da':'#0d8609';
        $ttf = strtoupper(substr(PHP_OS,0,3))==='WIN'?'C:/Windows/Fonts/STXINWEI.TTF':'/usr/share/fonts/win/fangzheng.TTF';//DejaVuSans-Bold.ttf
        $img->resizeCanvas($w, $h+64, 'center', false, '#ffffff')
        ->resizeCanvas($w, $h+124, 'bottom', false, '#ffffff')
        ->text('￥'. price_format($name), $w/2, 5, function($font) use ($ttf) {
                $font->file($ttf);
                $font->size(84);
                $font->color('#f44336');
                $font->align('center');
                $font->valign('top');
            })
        ->text('过期后请勿支付', $w/2, 80, function($font) use ($color, $ttf) {
                $font->file($ttf);
                $font->size(32);
                $font->color($color);
                $font->align('center');
                $font->valign('top');
            })
        ->text('过期时间 '.date('Y-m-d H:i:s', $exp), $w/2, $h+114, function($font) use ($color, $ttf) {
                $font->file($ttf);
                $font->size(32);
                $font->color($color);
                $font->align('center');
            })
        ->save($dirName);
        
        //$redis = Redis::connection();
        //$redis->hSet('pay', $uniqid, time());
        return is_file($dirName)?env('APP_URL').'/storage/pay/' . $uniqid : false;
    }
}
