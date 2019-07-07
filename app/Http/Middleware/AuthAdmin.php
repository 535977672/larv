<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{
    /**
     * admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //游客
        if (Auth::guard('admin')->guest()) {
            //是否ajax请求 是否json返回
            if ($request->ajax() || $request->wantsJson()) { 
                return return_ajax(200, '请先登录');
            } else {
                return redirect()->guest('admin/login'); 
            } 
        }

        return $next($request);
    }
}
