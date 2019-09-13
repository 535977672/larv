<?php
namespace App\Service;

class Service {
    
    protected $msg = '';
    protected $code = 0;

    public function setErrorMsg($msg, $code = 0){
        $this->msg = $msg;
        $this->code = $code;
        return false;
    }
    
    public function getErrorMsg(){
        return $this->msg;
    }
    
    public function getErrorCode(){
        return $this->code;
    }
    
}
