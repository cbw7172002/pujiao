<?php

namespace App\Http\Middleware;

use Closure;

class CheckUsersMiddleware
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
         if(\Auth::check() && \Auth::user()->type == 3){
             echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><script>alert('抱歉，请先退出管理员账号再尝试操作..');window.location.href='/'</script>";
             exit;
         }
        return $next($request);
    }
}
