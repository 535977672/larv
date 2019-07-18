<?php
namespace App\Service;

use App\Model\Order;
use App\Service\Pay;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * 
 */
class Order extends Service{

    public function createOrder($param){
        $pay = new Pay();
        try {
            DB::beginTransaction();
            
            
            
            
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollBack();
            $this->setErrorMsg($exc->getMessage());
            return false;
        }
    }
    
}
