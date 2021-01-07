<?php
namespace app\http\middleware;

use think\Request;

class unitLoginCheck
{
    public function handle(Request $request, \Closure $next)
    {
        if (empty(session('unit'))){
            return redirect('/unit/login');
        }
        return $next($request);
    }
}

