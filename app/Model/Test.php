<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;//软删除

use Illuminate\Database\Eloquent\Builder;

class Test extends Model
{
    /**
     * 与模型关联的数据表
     * 默认使用类的「蛇形名称」、复数形式名称
     * 默认tests
     * @var string
     */
    protected $table = 'test';
    
    //主键 默认id
    protected $primaryKey = 'id';
    
    //默认情况下主键将自动的被强制转换为 int 取消$incrementing设为false
    public $incrementing = true;
    
    /**
     * 该模型是否被自动维护时间戳
     * created_at
     * updated_at
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 模型的日期字段保存格式。
     *
     * @var string
     */
    protected $dateFormat = 'U';
    
    //自定义用于存储时间戳的字段名
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    
    /**
     * 此模型的连接名称。
     * 默认 'default' => env('DB_CONNECTION', 'mysql'),
     * @var string
     */
    protected $connection = 'mysql';
    
    /**
     * 可以被批量赋值的属性。
     * 批量赋值
     * 需要先在你的模型上定义一个 fillable 或 guarded 属性
     * 过滤 HTTP 请求传入了非预期的字段参数
     * @var array
     */
    protected $fillable = ['id', 'name', 'code', 'json', 'times'];
    
    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    //protected $guarded = ['code'];
    
    /**
     * 需要被转换成日期的属性。
     * 软删除
     * @var array
     */
    //protected $dates = ['is_delete'];
    
    /**
     * 数据模型的启动方法
     * 匿名的全局作用域
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        //闭包的全局作用域
        static::addGlobalScope('id', function(Builder $builder) {
            $builder->where('id', '<', 20);
        });
        //age 标识符来移除全局作用
        //Test::withoutGlobalScope('id')->get();
        
    }
    
    /**
     * 本地作用域
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeId($query, $type = 1)
    {
        return $query->where('id', '<', $type);
        //Test::id(1)->get();
    }

    //事件
    //Eloquent 模型会触发许多事件，让你在模型的生命周期的多个时间点进行监控：
    //creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored
    //当一个新模型被初次保存将会触发 creating 以及 created 事件。
    //如果一个模型已经存在于数据库且调用了 save 方法，将会触发  updating 和 updated 事件。
    //在这两种情况下都会触发 saving 和 saved 事件。
    /**
     * 模型的时间映射。
     *
     * @var array
     */
    protected $dispatchesEvents = [
        //'saved' => TestSaved::class,
        //'deleted' => TestDeleted::class,
    ];
    
    
    //定义关联
    // 1&1
    public function user()
    {
        return $this->hasOne('App\Model\User', 'id');
        //hasOne($related, $foreignKey = user_id, $localKey = $this->primaryKey)
        //Test::find(1)->user;
        //$re = Test::all();
        //foreach ($re as $r) {
        //    var_dump($r->user);
        //    var_dump($r->user()->select('name')->get()->toArray());
        //}
        
        //预加载 只执行2次
        //$books = Test::with(['user' => function ($query) {
        //    $query->where('name', 'like', '%first%');
        //}]])->get();
        //foreach ($books as $book) {
        //    echo $book->user->name;
        //}
        
        //延迟预加载
        //$books->load(['user' => function ($query) {
        //    $query->orderBy('name', 'asc');
        //}]);
        
        //插入 & 更新关联模型
        //$post = Test::find(1);
        //$post->user()->save(new App\User(['name'=>111]));
    }
    
    // 1 & n
    public function comments()
    {
        return $this->hasMany('App\Model\Comment', 'id');
        //Test::::find(1)->comments()->where('name', 'foo')->first();
    }
    
    
    /**
     * 访问器
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
    
    /**
     * 修改器
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
    
    //查询属性类型转换
    /**
     * 应该被转换成原生类型的属性。
     * integer real float double string 
     * boolean object array collection 
     * date datetime timestamp
     *
     * 数组 & JSON 转换
     * 
     * @var array
     */
    protected $casts = [
        'code' => 'integer',
        'json' => 'array'//保存时自动json
    ];

    public static function addFirstOrCreate($key, $data) {
        return self::firstOrCreate($key, $data);
    }
}
