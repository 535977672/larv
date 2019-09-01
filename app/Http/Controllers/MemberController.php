<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    
    public function index()
    {
        return $this->successful();
    }
}
