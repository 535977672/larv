<?php
namespace App\Service;

use App\Model\PayRecord;
use App\Model\PayCode;
use App\Model\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use \Exception;

/**
 * 
 */
class Pay extends Service{

    /**
     * 回调通知
     * @param type string $pkg
     * @param type string $title
     * @param type string $content
     * @param type int $type
     * @return boolean
     */
    public function notification($pkg, $title, $content, $type){
        if(!(($pkg == 'com.eg.android.AlipayGphone' && $type == 1) || ($pkg == 'com.tencent.mm' && $type == 2))){
            $this->setErrorMsg('包信息错误');
            return false;
        }
        $money = $this->checkMsgValid($content, $type);
        if($money === false) return false;
        $unique = $this->payMoneyValid($money, $type);
        if($unique === false) return false;
        return $this->orderStatusPay($unique['id'], $unique['o_id'], 3, '回调成功');
    }
    
    /**
     * 验证消息的合法性
     * @param type $content
     * @param type $gateway
     * @return boolean/int
     */
    private function checkMsgValid($content, $gateway) {
        if ($gateway === 2) {
            //微信支付的消息格式
            //1条：标题：微信支付，内容：微信支付收款0.01元(朋友到店)
            //多条：标题：微信支付，内容：[4条]微信支付: 微信支付收款1.01元(朋友到店)
            preg_match('/(\[\+?\d+条])?微信支付:|微信支付收款/',$content,$match);
            if(isset($match[0]) && !empty($match[0])){
                return $this->parseMoney($content);
            }
        } else if ($gateway === 1) {
            //支付宝的消息格式，标题：支付宝通知，内容：支付宝成功收款1.00元。
            preg_match('/支付宝成功收款/',$content,$match);
            if(isset($match[0]) && !empty($match[0])){
                return $this->parseMoney($content);
            }
        }
        $this->setErrorMsg('非收款消息');
        return false;
    }
    
    /**
     * 提取金额 单位分
     * @param type $content
     * @return boolean/int
     */
    private function parseMoney($content) {
        //收款(([1-9]\\d*)|0)(\\.(\\d){0,2})?元
        preg_match('/收款((([1-9]\d*)|0)(\.(\d){2})?)元/',$content,$match);
        if(isset($match[1]) && !empty($match[1]) && $match[1] != '0' && $match[1] != '0.00'){
            return intval(floatval($match[1])*100);
        }
        $this->setErrorMsg('提取金额失败');
        return false;
    }
    
    /**
     * 数据库唯一验证
     * @param type int $money
     * @return boolean/array
     */
    public function payMoneyValid($money, $type) {
        $time = time();
        $data = PayRecord::where([
                ['expiring', '>', $time],
                ['create_time', '<', $time],
                ['status', '=', 0],
                ['type', '=', $type],
                ['money', '=', $money]
            ])->get();
        if($data->isNotEmpty() && $data->count() == 1){
            return $data->toArray()[0];
        }
        if($data->count() > 1){
            foreach ($data as $key => $value) {
                $value->status = 6;
                $value->note = $value->note . '--数据重复检查';
                $value->save();
            }
        }
        $this->setErrorMsg($data->isEmpty()?'没有找到数据':'找到'.$data->count().'个数据');
        return false;
    }
    
    /**
     * 支付成功数据更新
     * 回调成功，手动验证成功
     * @param type $id
     * @param type $o_id
     * @param type $status
     * @param type $note
     * @return boolean
     * @throws Exception
     */
    public function orderStatusPay($id, $o_id, $status = 3, $note = '') {
        try {
            DB::beginTransaction();
            $order = Order::find($o_id);
            $payRecord = PayRecord::find($id);
            $payRecord->status = $status;
            $payRecord->complete_time = time();
            $payRecord->note = $payRecord->note . '--' . $note;
            if(!$payRecord->save()){
                throw new Exception('支付状态更新失败');
            }
            $order->order_status = 1;
            $order->pay_status = 1;
            if(!$order->save()){
                throw new Exception('订单状态更新失败');
            }
            Cache::store('redis')->tags(['payGoodsMoney'])->forget($payRecord->money);
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollBack();
            $this->setErrorMsg($exc->getMessage());
            return false;
        }
    }
    
    /**
     * 过期自动检查脚本 延时60s
     * @return boolean
     */
    public function payExpireCheck() {
        $time = time()-60;
        $data = PayRecord::where([
                ['expiring', '<', $time],
                ['status', '=', 0]
            ])->get();
        if($data->count() > 1){
            foreach ($data as $value) {
                $value->status = 2;
                $value->note = $value->note . '--自动检查过期' . date('Y-m-d H:i:s', $time+60);
                $value->save();
            }
        }
        return true;
    }
    
    /**
     * 手动过期检查
     * @param type array $ids
     * @return boolean
     */
    public function payPersonExpireCheck($ids) {
        $data = PayRecord::find($ids);
        if($data->count() > 1){
            foreach ($data as $value) {
                $value->status = 1;
                $value->note = $value->note . '--手动检查过期' . date('Y-m-d H:i:s', time());
                $value->save();
            }
        }
        return true;
    }
    
    /**
     * 设置PayCode
     * @param type $phone
     * @param type $code
     * @return type bool/coll
     */
    public function setPayCode($phone, $code) {
        $oldCode = PayCode::where('code', $code)->get();
        if($oldCode->isNotEmpty()){
            $this->setErrorMsg('数据重复');
            return false;
        }
        return PayCode::create([
                'code' => $code,
                'phone' => $phone,
                'create_time' => time()
            ]);
    }
    
    /**
     * 未过期金额
     * @param type $type
     * @return type array
     */
    public function getMoney($type) {
        $time = time();
        $data = PayRecord::where([
                ['expiring', '>', $time],
                ['create_time', '<', $time],
                ['status', '=', 0],
                ['type', '=', $type]
            ])->pluck('money');
        return $data->toArray();
    }
    
    public function aList($data, $limit = 20, $field = '*'){
        $where = [];
        isset($data['status']) && $data['status'] >=0 && $where[] = ['status', '=', $data['status']];
        isset($data['type']) && $data['type']  && $where[] = ['type', '=', $data['type']];
        isset($data['start']) && $data['start'] && $where[] = ['create_time', '>=', strtotime($data['start'] . ' 00:00:00')];
        isset($data['end']) && $data['end'] && $where[] = ['create_time', '<=', strtotime($data['end'] . ' 23:59:59')];
        $list = PayRecord::where($where)
            ->orderBy('id', 'desc')
            ->select(DB::raw($field))
            ->paginate($limit);
        return $list;
    }
    
    public function getPayRecordByoId($oid){
        return PayRecord::where('o_id', $oid)->first();
    }
}
