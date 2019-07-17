<?php
namespace App\ModelServer;

class Servers {
    
    protected $msg = '';
    protected $code = 0;

    public function setErrorMsg($msg, $code = 0){
        $this->msg = $msg;
        $this->code = $code;
    }
    
    public function getErrorMsg(){
        return $this->msg;
    }
    
    public function getErrorCode(){
        return $this->code;
    }
    
}
