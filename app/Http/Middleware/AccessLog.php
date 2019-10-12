<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class AccessLog
{
    /**
     * access log
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = [
            0,
            $request->ip(),
            $request->method(),
            $request->url(),
            $request->userAgent(),
            $request->get('rpm', ''),
            json_encode($request->all(), JSON_UNESCAPED_UNICODE),
            time()
        ];
        DB::insert('insert into access_log (`id`, `ip`, `method`, `url`, `agent`,`from`, `all`, `time`) values (?, ?, ?, ?, ?, ?, ?, ?)', $data);
        if(strpos($request->url(),'148.70.241.6') !== false) {exit;}
        return $next($request);
    }
}
