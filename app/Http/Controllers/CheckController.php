<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CheckController extends Controller
{
    public function __construct() {}
    
    public function machine()
    {
        return view('check.machine');
    }
    
    //验证后标记session
    public function ses()
    {
        session(['ses' => 'KSDJ@*@&S']);
        return redirect('/');
    }
}
