<?php

/* 
 * Github WebHooks代码部署
 */
$target = '/var/www/larv/public'; // 生产环境 web 目录
//密钥
$secret = "wqddede";
//获取 GitHub 发送的内容
$json = file_get_contents('php://input');
$content = json_decode($json, true);
//github 发送过来的签名
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
if (!$signature) {
   return http_response_code(404);
}
list($algo, $hash) = explode('=', $signature, 2);
//计算签名
$payloadHash = hash_hmac($algo, $json, $secret);
// 判断签名是否匹配
if ($hash === $payloadHash) {
    //输出和错误都写到文件
    $cmd = "cd $target && git pull >../hook.log 2>&1";
    //shell_exec — 通过 shell 环境执行命令，并且将完整的输出以字符串的方式返回。
    //无法通过返回值检测进程是否成功执行.
    $res = shell_exec($cmd);
    $res_log = 'Success:'.PHP_EOL;
    $res_log .= $content['head_commit']['author']['name'] . ' 在' . date('Y-m-d H:i:s') . '向' . $content['repository']['name'] . '项目的' . $content['ref'] . '分支 push 了' . count($content['commits']) . '个 commit：' . PHP_EOL;
    $res_log .= $res.PHP_EOL;
    $res_log .= '======================================================================='.PHP_EOL;
    echo $res_log;
} else {
    $res_log  = 'Error:'.PHP_EOL;
    $res_log .= $content['head_commit']['author']['name'] . ' 在' . date('Y-m-d H:i:s') . '向' . $content['repository']['name'] . '项目的' . $content['ref'] . '分支 push 了' . count($content['commits']) . '个 commit：' . PHP_EOL;
    $res_log .= '密钥不正确不能 pull'.PHP_EOL;
    $res_log .= '======================================================================='.PHP_EOL;
    echo $res_log;
}