<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('index.index');
//        return response()
//            ->view('index.index')
//            ->header('Cache-Control', 'max-age=7200')
//            ->header('Last-Modified', gmdate('D, d M Y H:i:s',time())." GMT")
//            ->header('Expires', gmdate('D, d M Y H:i:s', time() + 30)." GMT");
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
