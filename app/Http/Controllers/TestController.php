<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {

        $users = DB::table('info')->get();//从数据表中获取所有的数据列
        
        $users = DB::table('info')->where('title', 45)->first();//从数据表中获取一行数据
        
        $users = DB::table('info')->where('title', 45)->value('id');//单条记录中取出单个值
        
        $users = DB::table('info')->pluck('title');//获取一列的值 array
        
        $users = DB::table('info')->pluck('title', 'id');//array 'id'=>'title'
        
        $users = DB::table('info')->pluck('title', 'id');//array 'id'=>'title'
        
        
        var_dump($users);
        
        
        
        
        
        
        
        return return_ajax(200,'1212');
    }
}
