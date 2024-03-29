<?php
namespace App\Service;

use App\Common\Ucpaas;
use Illuminate\Support\Facades\Cache;
use Qcloud\Sms\SmsSingleSender;

/**
 * 短信发送
 */
class SmsSend extends Service{

    /**
     * 云之讯
     * @param type $uid
     * @param type $mobile
     * @param type $templateid 483727-验证码1  $param [code,time]
     * @param type $param
     * @return type
     */
    public function sendSms($uid, $mobile, $templateid, $param = []){

        //初始化必填
        //填写在开发者控制台首页上的Account Sid
        $options['accountsid']=env('YUNZHIXUN_ACCOUNT_SID', null);
        //填写在开发者控制台首页上的Auth Token
        $options['token']=env('YUNZHIXUN_TOKEN', null);

        //初始化 $options必填
        $ucpass = new Ucpaas($options);
        
        $appid = env('YUNZHIXUN_APPID', null);	//应用的ID，可在开发者控制台内的短信产品下查看
        //$templateid = "xxx";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
        //$param = $_POST['yzm']; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        //$mobile = $_POST['yzmtel'];
        //$uid = "";

        //70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

        try {
            $re = $ucpass->SendSms($appid,$templateid, implode(',', $param),$mobile,$uid);
            //{"code":"000000","count":"1","create_date":"2019-07-10 11:50:09","mobile":"18****7","msg":"OK","smsid":"6f8e8fa53a92fca1c2535754d8216e83","uid":"18***87"}
            $re = json_decode($re, true);
            //var_dump($re);

            if(!$re || $re['code'] != '000000') {
                $this->setErrorMsg($re['msg']);
                return false;
            }

            if($templateid == 483727){
                Cache::store('redis')->tags(['yzu'])->put('yzu_'.$mobile, $param[0], $param[1]/60);
            }
        
            return true;
        } catch(\Exception $e) {
            $this->setErrorMsg($e->getMessage());
            return false;
        }
    }
    
    /**
     * 云之讯
     * @param type $mobile
     * @param type $code
     * @return boolean
     */
    public function sendSmsVeri($mobile, $code){

        $oldCode = Cache::store('redis')->tags(['yzu'])->get('yzu_'.$mobile);
        if($oldCode != $code){
            return false;
        }
        return true;
    }
    
    
    /**
     * 腾讯云短信平台
     * @param type $uid
     * @param type $mobile
     * @param type $templateid 373486-验证码001  $param [code,time]
     * @param type $param
     * @return type
     */
    public function sendSmsTX($mobile, $templateid, $param = []){

        // 短信应用SDK AppID
        $appid = env('TENCENT_SMS_APPID', null);
        // 短信应用SDK AppKey
        $appkey = env('TENCENT_SMS_APPKEY', null);
        // 需要发送短信的手机号码
        //$phoneNumbers = $mobile;
        // 短信模板ID，需要在短信应用中申请
        //$templateId = 373486;
        // 签名
        $smsSign = "优甜缘";

        // 指定模板ID单发短信
        try {
            $ssender = new SmsSingleSender($appid, $appkey);
            $result = $ssender->sendWithParam("86", $mobile, $templateid, $param, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $re = json_decode($result, true);
                if(!$re || $re['result'] != '0') {
                $this->setErrorMsg($re['errmsg']);
                return false;
            }

            if($templateid == 373486){
                Cache::store('redis')->tags(['txsms'])->put('txsms_'.$mobile, $param[0], $param[1]/60);
            }

            return true;
        } catch(\Exception $e) {
            $this->setErrorMsg($e->getMessage());
            return false;
        }
    }
    
    /**
     * 腾讯云短信平台
     * @param type $mobile
     * @param type $code
     * @return boolean
     */
    public function sendSmsVeriTX($mobile, $code){

        $oldCode = Cache::store('redis')->tags(['txsms'])->get('txsms_'.$mobile);
        if($oldCode != $code){
            return false;
        }
        return true;
    }
    
}
