<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         if(\Auth::check() && \Auth::user()->type != 3){
             echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><script>if(confirm('请先退出前台用户账号，确定退出？')){window.location.href='/auth/logout'}else{window.location.href='/'}</script>";
             exit;
         }
        return $next($request);
    }
}
