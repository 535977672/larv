<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $request = '';
    
    public function __construct(Request $request) 
    { 
        $this->middleware('auth.admin:admin'); 
        $this->request = $request;
        $this->initialize();
    }
    
    protected function initialize() 
    { 
    }
}
