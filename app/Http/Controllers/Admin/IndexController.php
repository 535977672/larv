<?php

namespace App\Http\Controllers\Admin;

class IndexController extends AdminController
{
    public function index()
    {
        return view('admin.index.index', ['username'=>$this->request->user('admin')->name]);
    }
    
    public function main()
    {
        return view('admin.index.main', ['username'=>$this->request->user('admin')->name]);
    }
}
