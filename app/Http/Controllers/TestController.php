<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use App\Service\SmsSend;


class TestController extends Controller
{
    public function __construct() {}
    
    public function index()
    {
        //$this->dbTest();
       
        //$this->redisTest();
        
        //$this->cacheTest();
        
        //$this->cacheTagsTest();
        
        //$this->smsTest();
        
        $this->fileTest();
        
        return return_ajax(200,'1212');
    }
    
    protected function dbTest(){
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

        
        //获取和删除
        $value = Cache::pull('key21321312');

        
        //存储项目到缓存中
        Cache::put('key', 'value', 1);//$minutes
        
        
        //写入目前不存在的项目
        Cache::add('key', 'value', 2);
        
        //永久写入项目
        Cache::forever('key', 'value');
        
        //从缓存中移除项目
        Cache::forget('key');
        //清空所有缓存
        //Cache::flush();
         
    }
    
    protected function cacheTagsTest(){
        
        //缓存标签
        //缓存标签并不支持使用 file 或 database 的缓存驱动

        Cache::store('redis')->tags(['u1', 'u2'])->put('key_tags1', '1234', 1);
        Cache::store('redis')->tags(['u3', 'u4'])->put('key_tags2', '12345', 1);
        Cache::store('redis')->tags(['u2', 'u3'])->put('key_tags3', '123456', 1);
        Cache::store('redis')->tags(['u5'])->put('key_tags4', '1234567', 1);
        Cache::store('redis')->tags(['u5'])->put('key_tags5', '1234568', 1);
        Cache::store('redis')->tags(['u5'])->put('key_tags6', '1234569', 1);
        
        //错误
        //$value = Cache::store('redis')->get('key_tags1');
        //$value = Cache::store('redis')->tags(['u3'])->get('key_tags1');
        
        //获取时tags(['u1', 'u2'])要顺序一样
        $value = Cache::store('redis')->tags(['u1', 'u2'])->get('key_tags1');
        $value = Cache::store('redis')->tags(['u5'])->get('key_tags6');
        
        
        Cache::store('redis')->tags(['u2'])->flush();
        
        //Cache::store('redis')->flush();
        Cache::store('redis')->tags(['u5'])->forget('key_tags6');
        
    }
    
    protected function smsTest(){
        $sms = new SmsSend();
        $phone = '18';
        $code = '422123';
        $re = $sms->sendSms($phone, $phone, 483727, [$code, 180]);
        var_dump($re);
        
        $re = $sms->sendSmsVeri($phone, $code);
        var_dump($re);
    }
    
    protected function fileTest(){
        // 自动为文件名生成唯一的 ID...
        //Storage::putFile('photos', new File('r/test'));

        // 手动指定文件名...
        //Storage::putFileAs('photos', new File('r/test'), 'photo.jpg');
        
        Storage::put('test/file.txt', 'Contents');
        $contents = Storage::get('test/file.txt');//Contents
        $url = Storage::url('test/file.txt');// http://test.larv.com/storage/test/file.txt
        //echo $url;
        
        //可见性
        Storage::put('test/file5.txt', 'Contents', 'public');
        Storage::put('test/file6.txt', 'Contents', 'private');
        Storage::put('test/file7.txt', 'Contents', 'private');
        
        echo Storage::getVisibility('test/file6.txt');
        Storage::setVisibility('test/file6.txt', 'public');
        echo Storage::getVisibility('test/file6.txt');
        
        //Storage::copy('test/file.txt', 'test/file2.txt');

        //Storage::move('test/file2.txt', 'test2/file3.txt');
    }
}
