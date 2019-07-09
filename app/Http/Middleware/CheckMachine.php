<?php

namespace App\Http\Middleware;

use Closure;

class CheckMachine
{
    /**
     * wap机器访问检查
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $msession = session('ses', 0);//sesssion
        $lsession = $request->input('lses', 0);//sessionStorage
        $ajax = $request->ajax() || $request->wantsJson();
        $re = '';
        if ($lsession !== 'WDN@*DS' && $ajax) {
            $re = return_ajax(0, 'success');
        }
        if ($msession !== 'KSDJ@*@&S'){
            if ($ajax) {
                $re = return_ajax(0, 'success');
            } else {
                $re = redirect('/cd34/3miy/qoc4m/0jmzs');
            }
        }
        if($re){
            return $re;
        }

        return $next($request);
    }
}
