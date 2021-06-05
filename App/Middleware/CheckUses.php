<?php

namespace Extensions\Plugins\Forum_arckene__421339390\App\Middleware;

use Closure;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumAccess;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsers;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersNotification;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersSetting;
use Illuminate\Support\Facades\Route;

class CheckUses
{
    public function handle($request, Closure $next)
    {
        $remove = "Extensions\Plugins\Forum_arckene__421339390\App\Controllers\\";

        if(ForumAccess::orderByDesc('created_at')->where('user_ip', md5($request->server('REMOTE_ADDR')) )->first())
            $lAccess = ForumAccess::orderByDesc('created_at')->where('user_ip', md5($request->server('REMOTE_ADDR')) )->first();
        else
            $lAccess = null;


        if(is_null($lAccess) || strtotime(now()) - strtotime($lAccess['created_at']) > 2):
            ForumAccess::insert([
                'agent' => base64_encode(htmlentities($request->server('HTTP_USER_AGENT'))),
                'user_id' => (tx_auth()->isLogin()) ? user()->id : null,
                'user_ip' => md5($request->server('REMOTE_ADDR')),
                'uses' => str_replace($remove, "", Route::getCurrentRoute()->getAction('controller')),
                'parameters' => json_encode(Route::getCurrentRoute()->parameters(), true),
                'created_at' => now()
            ]);
        endif;

        if(tx_auth()->isLogin())
            if(userInfo(user()->id, 'banned'))
                return redirect()->action('HomeController@home');


        if (tx_auth()->isLogin()):
            if (!ForumUsers::whereId(user()->id)->count()):
                ForumUsers::insert([
                    'id' => user()->id,
                    'created_at' => now()
                ]);
            endif;
            if (!ForumUsersNotification::whereId(user()->id)->count()):
                ForumUsersNotification::insert([
                    'id' => user()->id,
                    'created_at' => now()
                ]);
            endif;
            if (!ForumUsersSetting::whereId(user()->id)->count()):
                ForumUsersSetting::insert([
                    'id' => user()->id,
                    'created_at' => now()
                ]);
            endif;
        endif;

        return $next($request);
    }
}