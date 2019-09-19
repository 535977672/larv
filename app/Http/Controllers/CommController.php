<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Pay;
use App\Model\NotifyLog;
use App\Service\Encrypt as EncryptService;

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
        $time = time();
        $pkg = $request->post('pkg', '');
        $title = $request->post('title', '');
        $content = $request->post('content', '');
        $type = intval($request->post('type', 0));
        $sign = $request->post('sign', '');
        $log = NotifyLog::create(['status' => 0, 'create_time' => $time, 'content' => json_encode($request->all(), JSON_UNESCAPED_UNICODE)]);

        $encrypt = EncryptService::encrypt($content . $pkg . $title . $type, env('API_PASSWORD'));
        if ($sign !== $encrypt) {
            $log->status = 2;
            $log->res = '解码失败';
            $contents = json_decode($log->content, true);
            $contents['encrypt'] = $encrypt;
            $log->content = json_encode($contents, JSON_UNESCAPED_UNICODE);
            $log->save();
            return return_ajax(0, $log->res, ['params' => $request->all(), $encrypt, $sign]);
        }
        
        $pay = new Pay;
        $re = $pay->notification($pkg, $title, $content, $type);
        if ($re === false) {
            $log->status = 2;
            $log->res = $pay->getErrorMsg();
            $log->save();
            return return_ajax(0, $pay->getErrorMsg(), ['params' => $request->all()]);
        }
        return return_ajax(200,'success');
    }
}

//Z8OewooDpcKowrrCqcKSwq3DimnCnGzDlMOLwrXCrsK+wqPCl8KWw5jDjGJsw5\/DnWLCsMK9aMOuf8K9wo\/CtcOawqDCgsKvw5rCvGjCpMKRw57Cl8OMw5vDksKEwqrDg8Kaw6XCm8KDw5nCqsKqw4TDgnbDn8KXw4PCnMOmw5TCtW3CrsOSw47ClMKNwqnCkMK1wpvDpsK0wonCpsOPw43DpnHCpMOjw4nCncOTwr55wrQ=
//Z8OewovDpcKowrrCqcKSwq3DimnCnGzDlMOLwrXCrsK+wqPCl8KWw5jDjGJsw5\/DnWLCsMK9aMOuf8K9wo\/CtcOawqDCgsKvw5rCvGjCpMKRw57Cl8OMw5vDksKEwqrDg8Kaw6XCm8KDw5nCqsKqw4TDgnbDn8KXw4PCnMOmw5TCtW3CrsOSw47ClMKNwqnCkMK1wpvDpsK0wonCpsOPw43DpnHCpMOjw4nCncOTwr55wrQ=



