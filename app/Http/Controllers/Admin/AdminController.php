<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct() 
    { 
        $this->middleware('auth.admin:admin'); 
        $this->initialize();
    } 
    
    protected function initialize() 
    { 
    }
}
