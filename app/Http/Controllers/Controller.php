<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $request = '';
    public function __construct(Request $request) 
    { 
        $this->middleware('machine'); 
        $this->request = $request;
        $this->initialize();
    }
    
    protected function initialize() 
    { 
    }
    
    protected function failed($msg = 'fail', $data = [], $status = 0) 
    { 
        if(is_array($msg) || is_object($msg)){
            $data = $msg;
            $msg = 'fail';
        }
        if($this->request->ajax() || $this->request->wantsJson()){
            return return_ajax($status, $msg, $data);
        }else{
            if(!isset($data['msg'])) $data['msg'] = $msg;
            if(!isset($data['status'])) $data['status'] = $status;
            return view('layouts.400', $data);
        }
    }
    
    protected function successful($msg = 'success', $data = [], $status = 200)
    { 
        if(is_array($msg) || is_object($msg)){
            $data = $msg;
            $msg = 'success';
        }
        if($this->request->ajax() || $this->request->wantsJson()){
            return return_ajax($status, $msg, $data);
        }else{
            if(!$msg || $msg == 'success'){
            $action = $this->request->route()->getActionName();
            list($class, $method) = explode('@', $action);
            $class = substr(strrchr($class,'\\'),1);
                $class = strtolower(str_replace('Controller', '', $class));
                $methodes = preg_split('/(?=[A-Z])/', $method);
                $method = strtolower(implode('_', $methodes));
                $view = $class.'.'.$method;
                if(strpos($action, '\\Controllers\\Admin\\')) $view = 'admin.'.$view;
            }else{
                $view = $msg;
            }
            return view($view, $data);
        }
    }
}
