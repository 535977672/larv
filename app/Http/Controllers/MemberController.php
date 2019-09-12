<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    
    public function index()
    {
        return $this->successful();
    }
    
    public function setTheme($theme)
    {
        $user = $this->request->user();
        if ($user && $user->id == 1) setTheme($theme);
        return $this->successful();
    }
}
