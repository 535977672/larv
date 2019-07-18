<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Pay;
use App\Model\NotifyLog;

class CommController extends Controller
{
    public function __construct() {}
    
    /**
     * 监听通知回调处理
     * @param Request $request
     * @return type
     */
    public function notification(Request $request)
    {
//        $time = time();
////        $pkg = $request->post('pkg', '');
////        $title = $request->post('title', '');
////        $content = $request->post('content', '');
////        $type = intval($request->post('type', 0));
//        $log = NotifyLog::create(['status' => 0, 'create_time' => $time, 'content' => json_encode($request->all(), JSON_UNESCAPED_UNICODE)]);
//        
//        $pkg = 'com.eg.android.AlipayGphone';
//        $title = 'test';
//        $content = '支付宝成功收款1.00元';
//        $type = 1;
//        
//        $pay = new Pay;
//        $re = $pay->notification($pkg, $title, $content, $type);
//        if($re === false) {
//            NotifyLog::create(['status' => 2, 'create_time' => $time, 'pid' => $log->id, 'content' => $pay->getErrorMsg()]);
//            return return_ajax(0, $pay->getErrorMsg(), ['params' => $request->all()]);
//        }
//        return return_ajax(200,'success');
        $pay = new Pay;
        $re = $pay->getMoney(1);
        var_dump($re);
    }
}
