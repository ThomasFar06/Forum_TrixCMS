<?php

namespace Extensions\Plugins\Forum_arckene__421339390\App\Middleware;

use Closure;

class CheckAuth
{
    public function handle($request, Closure $next)
    {
        if (!tx_auth()->isLogin())
            return redirect()->route('forum');

        return $next($request);
    }
}