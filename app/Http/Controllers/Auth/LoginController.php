<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    
    /**
     * 
     * @return type
     */
    public function showLoginForm()
    {
        return view('layouts.404');
    }

    /**
     * Where to redirect users after login.
     * 当用户成功通过身份认证后，他们会被重定向到 /home
     * 可以通过在 LoginController、RegisterController 和 ResetPasswordController中设置 redirectTo 属性来自定义重定向的位置
     * 如果重定向路径需要自定义生成逻辑，你可以定义 redirectTo 方法来代替 redirectTo 属性
     * @var string
     */
    protected function redirectTo(){
        return '/';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * Laravel 默认使用 email 字段来认证。如果你想用其他字段认证，可以在 LoginController 里面定义一个 username 方法
     */
    public function username()
    {
        return 'name';
    }
    
    
}
