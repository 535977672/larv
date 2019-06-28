<?php
/* 
 * composer.json 的 autoload 配置
 * ,"files": [
 *     "app/common.php"
 * ]
 * 修改完成后运行 composer dumpautoload
 */

function return_ajax($msg = 'success', $data = [], $status = 200){
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