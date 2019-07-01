<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    
    /**
     * /register
     * RegistersUsers注册视图重写
     * @return type
     */
    public function showRegistrationForm()
    {
        return view('layouts.404');
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     * 包含了应用验证新用户的规则，你可以按需要自定义该方法
     * validate
     * 普通页面验证错误重定向到先前的位置
     * ajax返回422状态码JSON
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => "bail|required|regex:'[0-9a-zA-z]{6,18}'|unique:users",
            //'email' => 'required|string|email|max:255|unique:users',
            'password' => "bail|required|regex:'[0-9a-zA-z]{6,18}'|confirmed",//和password_confirmation一致
        ], [
            'required' => '参数必须',
            'name.required' => '用户名不能为空',
            'name.regex' => '用户名格式错误',
            'name.unique' => '用户已被注册',
            'password.required' => '密码不能为空',
            'password.regex' => '密码格式错误',
            'password.confirmed' => '重复密码错误',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     * 使用 Eloquent ORM 在数据库中创建新的 App\User 记录。你可以根据数据库的需要自定义该方法。
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => '',
            'password' => bcrypt($data['password']),
        ]);
    }
    
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        //$this->validator($request->all())->validate();//提供自动跳转功能 自动重定向
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return return_ajax(0, $errors[0]);
        }

        event(new Registered($user = $this->create($request->all())));
        
        //登录
        $this->guard()->login($user);

        return return_ajax(200, '注册成功');
    }
}
