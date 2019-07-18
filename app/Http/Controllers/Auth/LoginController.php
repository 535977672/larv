<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     * 重写login视图
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
    
    /**
     * 重写login方法
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = $this->validateLogin($request->all());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return return_ajax(0, $errors[0]);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        //登录次数验证
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            //限制登录
            //return $this->sendLockoutResponse($request);
            return  $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        //登录失败次数
        $this->incrementLoginAttempts($request);

        return return_ajax(0, '用户或密码错误');
    }
    
    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return return_ajax(200, '登录成功');
    }
    
    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        
        return return_ajax(0, '操作锁定');
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return return_ajax(200, '退出成功');
    }
    
    /**
     * 登录验证重写
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(array $data)
    {
        return Validator::make($data, [
            $this->username() => "bail|required|regex:'^[1][3,4,5,6,7,8,9][0-9]{9}$'",
            'password' => "bail|required|regex:'[0-9a-zA-z]{6,18}'",
        ], [
            'name.required' => '手机不能为空',
            'name.regex' => '手机格式错误',
            'password.required' => '密码不能为空',
            'password.regex' => '密码格式错误'
        ]);
    }
    
    
}
