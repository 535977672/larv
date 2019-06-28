<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:18|unique:users',
            //'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:18|confirmed',
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
            //'email' => '',
            'password' => bcrypt($data['password']),
        ]);
    }
}
