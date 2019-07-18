<?php
/* 
 * composer.json 的 autoload 配置
 * ,"files": [
 *     "app/common.php"
 * ]
 * 修改完成后运行 composer dumpautoload
 */

function return_ajax($status = 200, $msg = 'success', $data = []){
    $re = [
        'status' => $status,
        'msg' => $msg
    ];
    if($data){
       $re['data'] =  $data;
    }
    return response()->json($re);
}

function is_weixin(){
    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
        return true;
    } else {
        return false;
    }
}

function is_qq(){
    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') !== false ) {
        return true;
    }
    return false;
}

function get_real_ip() {
    if (getenv('HTTP_CLIENT_IP')){
        $ip = getenv('HTTP_CLIENT_IP');
        //头是有的，只是未成标准，不一定服务器都实现了
    } elseif (getenv('HTTP_X_FORWARDED_FOR')){
        $ips = getenv('HTTP_X_FORWARDED_FOR');
        $ips = explode(',', $ips);
        $ip = $ips[0];
        //反向代理 是有标准定义，用来识别经过HTTP代理后的客户端IP地址
    } elseif (getenv('REMOTE_ADDR')){
        $ip = getenv('REMOTE_ADDR'); 
        //如果用了代理，获取到的是代理服务器ip
        //也有可能被路由伪造,因为REMOTE_ADDR 是底层的回话ip地址，路由是可以发起伪造
        //使用代理绕过 REMOTE_ADDR
    } else {
        $ip = 'unknown';
    }
    return $ip;
}

function getRandStr($length = 6, $type = 1){
    if ($type == 1) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789';
    } else if ($type == 2) {
        $chars = 'abcdefghijklmnopqrstuvwxyz';
    } else if ($type == 3) {
        $chars = '0123456789';
    }
    $len = strlen($chars);
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[rand(0, $len - 1)];
    }
    return $str;
}

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