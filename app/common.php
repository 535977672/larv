<?php
/* 
 * composer.json 的 autoload 配置
 * ,"files": [
 *     "app/common.php"
 * ]
 * 修改完成后运行 composer dumpautoload
 */

use Illuminate\Support\Facades\Cache;

/**
 * 返回
 * @param type $status
 * @param type $msg
 * @param type $data
 * @return type
 */
function return_ajax($status = 200, $msg = 'success', $data = []){
    $re = [
        'status' => $status,
        'msg' => $msg,
        'data' => $data
    ];
    return response()->json($re);
}

/**
 * 微信浏览器
 * @return boolean
 */
function is_weixin(){
    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
        return true;
    } else {
        return false;
    }
}

/**
 * QQ浏览器
 * @return boolean
 */
function is_qq(){
    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') !== false ) {
        return true;
    }
    return false;
}

/**
 * 获取IP
 * @return string
 */
function get_real_ip() {
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
        //头是有的，只是未成标准，不一定服务器都实现了
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']){
        $ips = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $ips = explode(',', $ips);
        $ip = $ips[0];
        //反向代理 是有标准定义，用来识别经过HTTP代理后的客户端IP地址
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']){
        $ip = $_SERVER['REMOTE_ADDR']; 
        //如果用了代理，获取到的是代理服务器ip
        //也有可能被路由伪造,因为REMOTE_ADDR 是底层的回话ip地址，路由是可以发起伪造
        //使用代理绕过 REMOTE_ADDR
    } else {
        $ip = 'unknown';
    }
    return $ip;
}

/**
 * 随机字符串
 * @param type $length
 * @param type $type
 * @return string
 */
function getRandStr($length = 6, $type = 1){
    if ($type == 1) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ123456789';
    } else if ($type == 2) {
        $chars = 'abcdefghijklmnopqrstuvwxyz';
    } else if ($type == 3) {
        $chars = '123456789';
    }
    $len = strlen($chars);
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[rand(0, $len - 1)];
    }
    return $str;
}

/**
 * 字符串过滤
 * @param type $str
 * @param type $type
 * @return type
 */
function F($str, $type = 1){
    if(!$str){
        return $str;
    }
    if ($type == 1 || $type == 'string') {
        $str = trim(strip_tags($str));
    } else if ($type == 2 || $type == 'int') {
        $str = intval($str);
    } else if ($type == 3 || $type == 'float') {
        $str = floatval($str);
    } else if ($type == 4 || $type == 'html') {
        $str = trim(htmlspecialchars($str));
    }
    return $str;
}

function FS($str){
    return F($str, 1);
}

function FI($str){
    return F($str, 2);
}

function FF($str){
    return F($str, 3);
}

function FH($str){
    return F($str, 4);
}

/**
 * 金额格式化
 * @param type int $money
 * @return string
 */
function price_format($money){
    return !$money ? '0.00' : sprintf("%.2f",$money/100);
}

function setTheme($theme)
{
    return Cache::store('redis')->tags(['theme'])->put('theme', $theme, 518400);
}

function getTheme()
{
    $arr = theme();
    $theme = Cache::store('redis')->tags(['theme'])->get('theme', 'ec');
    return isset($arr[$theme])?$arr[$theme]:$arr['ec'];
}

function theme()
{
    return $arr = [
        'white' => 'white',
        'f5' => '#f5f5f5',
        '00bec8' => '#00bec8',
        '1989fa' => '#1989fa',
        'e3383e' => '#e3383e',
        'F44336' => '#F44336',
        'f44' => '#f44',
        'E91E63' => '#E91E63',
        '673AB7' => '#673AB7',
        'FF5722' => '#FF5722',
        '4CAF50' => '#4CAF50',
        'f50bc1' => '#f50bc1',
        'rgb1' => 'rgba(156, 39, 176, 0.17)',
        'rgb2' => 'rgba(255, 193, 7, 0.18)',
        'rgb2' => 'rgb(237, 246, 247)'
    ];
}