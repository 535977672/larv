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
        
        if(!is_file($fileName)){
            //$this->setErrorMsg('文件不存在');
            //return false;
            $fileName = $baseDir . 'paycomm.jpg';
        }
        
        $img = Image::make($fileName);
        $w = $img->width();
        $h = $img->height();
        $color = $type == 1?'#2275da':'#0d8609';
        $ttf = strtoupper(substr(PHP_OS,0,3))==='WIN'?'C:/Windows/Fonts/STXINWEI.TTF':'/usr/share/fonts/win/simhei.ttf';//DejaVuSans-Bold.ttf
        $img->resizeCanvas($w, $h+32, 'center', false, '#ffffff')
        ->resizeCanvas($w, $h+50, 'bottom', false, '#ffffff')
        ->text('￥'. price_format($name), $w/2, 10, function($font) use ($ttf) {
                $font->file($ttf);
                $font->size(32);
                $font->color('#f44336');
                $font->align('center');
                $font->valign('top');
            })
        ->text('过期后请勿支付', $w/2, 5, function($font) use ($color, $ttf) {
                $font->file($ttf);
                $font->size(16);
                $font->color($color);
                $font->align('center');
                $font->valign('top');
            })
        ->text('过期时间 '.date('Y-m-d H:i:s', $exp), $w/2, $h+45, function($font) use ($color, $ttf) {
                $font->file($ttf);
                $font->size(16);
                $font->color($color);
                $font->align('center');
            })
        ->save($dirName);
        
        $redis = Redis::connection();
        $redis->hSet('pay', $uniqid, time());
        return env('APP_URL').'/storage/pay/' . $uniqid;
    }
}
