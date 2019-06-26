<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('index.index');
    }
    
    public function main()
    {
        return view('index.main');
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
}
