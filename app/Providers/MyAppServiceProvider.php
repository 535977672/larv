<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyAppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * 启动任意应用服务
     * @return void
     */
    public function boot(Request $request)
    {
        //数据共享给所有视图
        View::share('isWeixin', is_weixin() || is_qq());
        View::share('requestes', $request->all());
    }

    /**
     * Register any application services.
     * 注册服务提供者
     * @return void
     */
    public function register()
    {
        //
    }
}
