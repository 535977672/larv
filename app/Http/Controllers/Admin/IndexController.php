<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IndexController extends AdminController
{
    public function index(Request $request)
    {
        return view('admin.index.index');
    }
    
    public function main()
    {
        return view('admin.index.main');
    }
}
