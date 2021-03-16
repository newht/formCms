<?php

namespace app\http\middleware;

use think\Request;

class userLogin
{
    public function handle(Request $request, \Closure $next)
    {
        if (empty(session('user'))){
            return redirect('/user/login');
        }

        return $next($request);
    }
}
