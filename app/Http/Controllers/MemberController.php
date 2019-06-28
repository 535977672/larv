<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function login(Request $request)
    {
//        $this->validate($request, [
//            'name' => "bail|required|regex:['[0-9a-zA-z]{10,18}']",
//            'pwd' => "bail|required|regex:['[0-9a-zA-z]{10,18}']",
//        ]);
        session(['userid'=>1]);
        $cookie = cookie('userid', 2, 2);
        return return_ajax(200, 'success', ['name' => $request, 'se'=>session('userid')])->cookie($cookie);
    }
    
    public function register(Request $request)
    {
//        $this->validate($request, [
//            'name' => "bail|required|regex:['[0-9a-zA-z]{10,18}']",
//            'pwd' => "bail|required|regex:['[0-9a-zA-z]{10,18}']",
//        ]);

        return return_ajax(200, 'success', ['name' => $request, 'se'=>session('userid')]);
    }
    
    public function search(Request $request)
    {
        if($request->isMethod('post')){
            return response()->json([
                'name' => 'Abigail',
                'state' => 'CA'
            ]);
        }
        return view('index.search');
    }
    
    
    
    public function me(Request $request)
    {
        return view('index.me');
    }
    
    
    
    public function see(Request $request)
    {
        return view('index.see');
    }
    
    
    
    public function detail($id)
    {
        
        return view('index.detail', ['id'=>$id]);
    }
}
