<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;


class TestController extends Controller
{
    public function index()
    {

        //运行原生的 SQL 语句
        DB::transaction(function () {
            
            $users = DB::select('select * from info where title = :title', ['title' => 45]);//数组结果集
            
            //$users = DB::insert('insert into info (id, title) values (?, ?)', [0, 'Dayle']);//bool
            
            $users = DB::update('update info set title = "eee" where id = ?', ['334332']);//影响行数
            
            $users = DB::delete('delete from info where id = ?', ['334332']);//影响行数
            
        }, 5);//发生死锁时，应该重新尝试事务的次数
        
        //或
        DB::beginTransaction();
        DB::rollBack();
        DB::commit();
        
        
        
        $users = DB::table('info')->get();//对象 从数据表中获取所有的数据列
        
        $users = DB::table('info')->where('title', 45)->first();//对象  从数据表中获取一行数据
        
        $users = DB::table('info')->where('title', 45)->value('id');//value 单条记录中取出单个值
        
        $users = DB::table('info')->pluck('title');//对象 获取一列的值 array 'num'=>'title'
       
        $users = DB::table('info')->pluck('title', 'id');//对象  array 'id'=>'title'
        
        $users = DB::table('info')->pluck('title', 'id');//array 'id'=>'title'
        
        //结果分块
        DB::table('info')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                //var_dump($user);//对象  从数据表中获取一行数据
            }
            //停止对后续分块的处理
            return false;
        });
        
        //聚合
        $users = DB::table('info')->count();

        $users = DB::table('info')->max('id');

        $users = DB::table('info')->avg('id');
        
        //字段过滤
        //原始表达式 DB::raw
        $users = DB::table('info')->select(DB::raw('id, title as user_email'))->get();
        

        //Inner Join 语法
        $users = DB::table('info')
            ->join('users', 'info.id', '=', 'users.id')
            ->leftJoin('user as u1', 'info.id', '=', 'u1.id')
            ->leftJoin('user', function ($join) {
                $join->on('info.id', '=', 'user.id')
                     ->where('user.id', '>', 2);
            })
            ->select('info.*')
            ->get();
            
        //Where 子句
        $users = DB::table('info')
            ->where('id', '=', 334332)
            ->where('id',334333)
            ->orWhere([
                ['id', '=', 334333]
            ])
            ->orWhere(function ($query) {
                $query->where('id', '>', 100)
                      ->where('title', '=', 45);
                //or (id > 100 and title = 45)
            })
            //whereExists
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('user')
                      ->whereRaw('user.id = info.id');
                //exists (select 1 from user where user.id = info.id)
            })
            ->get();
         
            
        //对查询语句构造器进行分页
        $users = DB::table('info')->paginate(2);//对象 page
//        echo $users->count();
//        echo $users->currentPage();
//        echo $users->lastPage();
//        echo $users->nextPageUrl();
        //{{ $users->links() }}
        //$users = $users->toJson();
        $users = $users->toArray();
            
            
        //var_dump($users);
       
        $this->redisTest();
        
        $this->cacheTest();
        
        return return_ajax(200,'1212');
    }
    
    protected function redisTest(){
        
        //redis/predis
        //$redis = Redis::connection('default');
        //管道化命令
        Redis::pipeline(function ($pipe) {
            for ($i = 0; $i < 10; $i++) {
                //$pipe->set("key:$i", $i);
            }
        });
        
    }
    
    
    
    protected function cacheTest(){
        
        $value = Cache::get('key');//value 或者 null
        $value = Cache::store('file')->get('key', 'default');
        
        //以将 Closure 作为默认值传递。如果指定的缓存项在缓存中不存在， Closure 的结果将被返回。
        //传递一个闭包允许你延迟从数据库或外部服务中取出默认值
        $value = Cache::get('key_1', function () {
            return 232132;
        });
                 

        $value = Cache::has('key21321312');
  
        Cache::increment('key21321312', 2);//不存在无效
        Cache::decrement('key21321312', 1);
        
        $value = Cache::get('key21321312');

        
        $value = Cache::remember('users', 5, function () {
            return 34324;
        });
        var_dump($value);
    }
}
