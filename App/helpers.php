<?php

use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumCustomization;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumIconType;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumForum;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumConfiguration;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersRank;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumRanks;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumRanksRp;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsers;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThread;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadLikes;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumPermissions;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumAccess;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersSetting;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

require_once 'HTMLPurifier/HTMLPurifier.auto.php';

if (!function_exists("paginate")) {

    function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}

if (!function_exists("iconify")) {
    function iconify($f)
    {
        if ($f['icon_type'] != 0) {
            $icon = ForumIconType::find($f['icon_type']);
            return str_replace("#icon#", $f['icon'], $icon['format']);
        } else {
            return "<img src='{$f['icon']}'>";
        }
    }
}

if (!function_exists("forumasize")) {
    function forumasize($f)
    {
        if ($f['size'] == 1) {
            return "w-25";
        } else if ($f['size'] == 2) {
            return "w-50";
        } else if ($f['size'] == 3) {
            return "w-75";
        } else {
            return "w-100";
        }
    }
}

if (!function_exists("slugify")) {
    function slugify($string, $delimiter = '-')
    {
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }
}

if (!function_exists("breadcrumb")) {
    function breadcrumb($forum)
    {
        $forums = ForumForum::get();
        $data[] = [
            "name" => $forum['name'],
            "route" => route('forum.forum', [slugify($forum['name']), $forum['id']])
        ];
        if ($forum['forum_id'] != 0):
            $forum_2 = $forums->find($forum['forum_id']);
            $data[] = [
                "name" => $forum_2['name'],
                "route" => route('forum.forum', [slugify($forum_2['name']), $forum_2['id']])
            ];
            if ($forum_2['forum_id'] != 0):
                $forum_3 = $forums->find($forum_2['forum_id']);
                $data[] = [
                    "name" => $forum_3['name'],
                    "route" => route('forum.forum', [slugify($forum_3['name']), $forum_3['id']])
                ];
                if ($forum_3['forum_id'] != 0):
                    $forum_4 = $forums->find($forum_3['forum_id']);
                    $data[] = [
                        "name" => $forum_4['name'],
                        "route" => route('forum.forum', [slugify($forum_4['name']), $forum_4['id']])
                    ];
                    if ($forum_4['forum_id'] != 0):
                        $forum_5 = $forums->find($forum_4['forum_id']);
                        $data[] = [
                            "name" => $forum_5['name'],
                            "route" => route('forum.forum', [slugify($forum_5['name']), $forum_5['id']])
                        ];
                    endif;
                endif;
            endif;
        endif;
        $data[] = [
            "name" => "Forum",
            "route" => route('forum')
        ];
        $data[] = [
            "name" => "<i class='fas fa-home'></i>",
            "route" => action('HomeController@home')
        ];
        return array_reverse($data);
    }
}

if (!function_exists("userRanks")) {
    function userRanks($user_id)
    {
        $ranks = ForumUsersRank::where('id', $user_id);
        if (count($ranks->get()) == 0) {
            return ForumRanks::where('default', 1)->get();
        } else {
            $list = [];
            foreach ($ranks->get() as $r) {
                $list[] = ForumRanks::find($r['rank_id']);
            }
            return collect($list)->sortBy('power');
        }
    }
}

if (!function_exists("userInfo")) {
    function userInfo($user_id, $attr)
    {
        $customization = ForumCustomization::first();
        if ($attr == "avatar") {
            if (ForumUsers::find($user_id)["avatar"] == null) {
                if ($customization['user__profile__avatar'] == 1) {
                    return route('forum.defaultProfile', [slugify(user($user_id)->pseudo), $user_id]);
                } else if ($customization['user__profile__avatar'] == 2) {
                    return "https://www.gravatar.com/avatar/" . md5(strtolower(user($user_id)->email)) . "?s=300";
                } else if ($customization['user__profile__avatar'] == 3) {
                    return "https://minotar.net/avatar/" . user($user_id)->pseudo . "/300";
                } else {
                    return route('forum.defaultProfile', [slugify(user($user_id)->pseudo), $user_id]);
                }
            } else {
                return ForumUsers::find($user_id)["avatar"];
            }
        } else if ($attr == "title") {
            if (ForumUsers::find($user_id)["title"]) {
                return ForumUsers::find($user_id)[$attr];
            } else {
                return __("forum_arckene::forum.empty_title");
            }
        } else if ($attr == "messages") {
            return count(ForumThread::where('author_id', $user_id)->get());
        } else if ($attr == "likes") {
            $likes = 0;
            foreach (ForumThread::where('author_id', $user_id)->get() as $t) {
                $likes += count(ForumThreadLikes::where('thread_id', $t['id'])->get());
            }
            return $likes;
        } else if ($attr == "ban") {
            return false;
        } else if ($attr == "staff") {
            return userRanks($user_id)->first()['staff'];
        } else {
            if(ForumUsers::find($user_id)[$attr] != null)
                return ForumUsers::find($user_id)[$attr];
        }
    }
}

if (!function_exists("smartFilter")) {
    function smartFilter($string)
    {

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($string);

    }
}

if (!function_exists("quoteShowing")) {
    function quoteShowing($thread)
    {
        return "<div class='quotes'><div class='info'>" .
            __('forum_arckene::forum.quotes', [
                'user' => user($thread['author_id'])->pseudo,
                'format' => dateFormat($thread['created_at'])
            ]) .
            "</div><div class='content'>" .
            threadMessage($thread['message']) .
            "</div><div class='show quotesFullShow'>...</div></div>";
    }
}

if (!function_exists("mentionShowing")) {
    function mentionShowing($id)
    {
        $id = (int)$id;

        $rank = userRanks($id)->first();
        return "<span class='forum-mention' style='background: " . $rank['background'] . "'><a style='color: " . $rank['color'] . "' href='" . route('forum.user', [user($id)->pseudo, $id]) . "'>" . user($id)->pseudo . "</a></span>";
    }
}

if (!function_exists("threadMessage")) {
    function threadMessage($string, $author_id = null)
    {
        $quotes = array();

        preg_match_all('/\[QUOTE message_id=(\d*)\]\[\/QUOTE\]/', $string, $quotes);
        if (key_exists(0, $quotes[0])) {
            for ($i = 0; $i < count($quotes[0]); $i++) {
                if (forum__getThreads()->find($quotes[1][$i]))
                    $string = str_replace($quotes[0][$i], quoteShowing(forum__getThreads()->find($quotes[1][$i])), $string);
            }
        }

        $mention = array();
        preg_match_all('/@[^#]+#(\d+)/', $string, $mention);
        if (key_exists(0, $mention[0])) {
            for ($i = 0; $i < count($mention[0]); $i++) {
                if (tx_model()->user->userExist((int)$mention[1][$i]))
                    $string = str_replace($mention[0][$i], mentionShowing($mention[1][$i]), $string);
            }
        }

        $string = smartFilter($string);

        if (!is_null($author_id)) {
            if (forum__userSetting($author_id, 'content__show_signature')):
                $string .= smartFilter(userInfo($author_id, 'signature'));
            endif;
        }
        return $string;
    }
}

if (!function_exists('forum__getFollow')) {
    function forum__getFollow($id = null)
    {
        if (is_null($id)) {
            return new \Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersFriend;
        } else {
            return \Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersFriend::get('id', $id);
        }
    }
}

if (!function_exists("isLiked")) {
    function isLiked($thread)
    {
        $likes = (new ForumThreadLikes());
        if (count($likes->where('user_id', user()->id)->where('thread_id', $thread['id'])->get()) > 0)
            return true;
        return false;
    }
}

if (!function_exists("dateFormat")) {
    function dateFormat($date)
    {
        if ((bool)strtotime($date))
            return ((strtotime(now()) - strtotime($date) < 86400) ? $date->diffForHumans() : __('forum_arckene::forum.date_format', ['date' => $date->format('d/m/Y'), 'hours' => $date->format('H\\hi')]));
    }
}

if (!function_exists("forumPermission")) {
    function forumPermission($permission)
    {
        if (tx_auth()->isLogin()) {
            $ranks = userRanks(user()->id);
            $permissions = (new ForumPermissions());
            foreach ($ranks as $r) {
                $rp = ForumRanksRp::where('rank_id', $r['id'])->where('permission_id', $permissions->where('name', $permission)->first()['id'])->first()['action'];
                if ($rp == 1)
                    return true;
                if ($rp == 2)
                    return false;
            }
            return false;
        } else {
            if ($permission == "FORUM_ACCESS")
                return true;
            return false;
        }
    }
}

if (!function_exists("forum__canPost")) {
    function forum__canPost($f)
    {
        $f = ForumForum::find($f);

        if (!tx_auth()->isLogin()) return false;

        $f_power = ForumRanks::find($f['watch_rank_id'])['power'];
        $u_power = userRanks(user()->id)->first()['power'];
        if ($f_power >= $u_power)
            return true;
        return false;
    }
}

if (!function_exists("forum__canSee")) {
    function forum__canSee($f)
    {
        if (!tx_auth()->isLogin()) {
            if ($f['watch_rank_id'] == ForumRanks::where('default', 1)->first()['id'])
                return true;
            return false;
        } else {
            $f_power = ForumRanks::find($f['watch_rank_id'])['power'];
            $u_power = userRanks(user()->id)->first()['power'];
            if ($f_power >= $u_power)
                return true;
            return false;
        }
    }
}

if (!function_exists('threadCount')) {
    function threadCount($id, $default = true)
    {
        $forums = ForumForum::get();
        $threads = new ForumThread();
        $toReturn = 0;
        if ($default) {
            $toReturn += count($threads->where('forum_id', $id)->where('delete', null)->orWhere('delete', 0)->get());
        }
        foreach ($forums->where('forum_id', $id) as $f) {
            $toReturn += count($threads->where('forum_id', $f['id'])->where('delete', null)->orWhere('delete', 0)->get());
            if ($f['category']) {
                threadCount($f['id'], false);
            }
        }
        return $toReturn;
    }
}

if (!function_exists('messageCount')) {
    function messageCount($id, $default = true)
    {
        $forums = ForumForum::get();
        $threads = new ForumThread();
        $toReturn = 0;
        if ($default) {
            $countable = $threads->where('forum_id', $id)->where('delete', null)->orWhere('delete', 0)->get();
            $toReturn += count($countable);
            foreach ($countable as $c) {
                $toReturn += count($threads->where('thread_id', $c['id'])->where('delete', null)->orWhere('delete', 0)->get());
            }
        }
        foreach ($forums->where('forum_id', $id) as $f) {
            $countable = $threads->where('forum_id', $f['id'])->where('delete', null)->orWhere('delete', 0)->get();
            $toReturn += count($countable);
            foreach ($countable as $c) {
                $toReturn += count($threads->where('thread_id', $c['id'])->where('delete', null)->orWhere('delete', 0)->get());
            }
            if ($f['category']) {
                messageCount($f['id'], false);
            }
        }
        return $toReturn;
    }
}

if (!function_exists('forumCount')) {
    function forumCount($id)
    {
        $forums = ForumForum::get();
        $threads = new ForumThread();
        $toReturn = 0;
        foreach ($forums->where('forum_id', $id) as $f) {
            $toReturn++;
            if ($f['category']) {
                $toReturn += forumCount($f['id']);
            }
        }
        return $toReturn;
    }
}

if (!function_exists('forum__getThreads')) {
    function forum__getThreads()
    {
        return ForumThread::orderByDesc('created_at')->where(function ($q) {
            return $q->where('delete', 0)->orWhere('delete', null);
        })->where(function ($q) {
            return $q->where('archive', 0)->orWhere('archive', null);
        });
    }
}

if (!function_exists('getLastThread')) {
    function getLastThread($id)
    {
        return forum__getThreads()->orderByDesc('created_at')->where('forum_id', $id)->first();
    }
}

if (!function_exists('rankName')) {

    function rankName($string)
    {

        if (substr($string, 0, 6) == "LANG::") {
            return __('forum_arckene::rank.' . substr($string, 6));
        }
        return $string;
    }
}

if (!function_exists('forum__getTags')) {
    function forum__getTags($forum, $user = null)
    {
        if ($user == null) {
            $user = user()->id;
        }
        $tags = new \Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadTags;
        $ranks_id = array();
        foreach (userRanks($user) as $r) {
            array_push($ranks_id, "{$r['id']}");
        }
        $forums_id = array();
        array_push($forums_id, "{$forum}");
        while (ForumForum::find($forum)['forum_id']) {
            $forum = ForumForum::find($forum)['forum_id'];
            array_push($forums_id, "{$forum}");
        }

        return $tags::when($ranks_id, function ($query) use ($ranks_id) {
            $query->where(function ($query) use ($ranks_id) {
                foreach ($ranks_id as $rid) {
                    $query->orWhereJsonContains('rank_id', $rid);
                }
                $query->orWhereJsonContains('rank_id', "All");
                $query->orWhere('rank_id', null);
            });
        })->when($forums_id, function ($query) use ($forums_id) {
            $query->where(function ($query) use ($forums_id) {
                foreach ($forums_id as $rid) {
                    $query->orWhereJsonContains('thread_id', $rid);
                }
                $query->orWhereJsonContains('thread_id', "All");
                $query->orWhere('thread_id', null);
            });
        })->get();
    }
}

if (!function_exists('forum__tags')) {
    function forum__tags($id)
    {
        $tags = new \Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadTags;
        return "<span style='display: inline-block;background: " . $tags->find($id)['background_color'] . ";color: " . $tags->find($id)['text_color'] . ";border-radius: 50px;padding: 0 0.5rem;font-size: 60%;'>" . $tags->find($id)['name'] . "</span>";
    }
}


if (!function_exists('forum__getUsers')) {
    function forum__getUsers()
    {
        return new ForumUsers;
    }
}

if (!function_exists('forum__getShares')) {
    function forum__getShares()
    {
        return new \Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumWidgetShare();
    }
}

if (!function_exists('forum__getFriends')) {
    function forum__getFriends()
    {
        return new \Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersFriend();
    }
}

if (!function_exists('forum__generateSharable')) {
    function forum__generateSharable()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $url = "https://";
        else
            $url = "http://";

        $url .= $_SERVER['HTTP_HOST'];

        $url .= $_SERVER['REQUEST_URI'];

        $url = htmlspecialchars($url);

        $list[] = [
            'https://www.facebook.com/sharer/sharer.php?u=' . $url,
            'https://twitter.com/share?url=' . $url,
            'https://www.reddit.com/submit?url=' . $url,
            'https://www.pinterest.com/pin/create/button/?url=' . $url,
            'https://www.tumblr.com/share/link?url=' . $url,
            'https://api.whatsapp.com/send?text=' . $url,
            'https://www.facebook.com/dialog/send?app_id=5303202981&display=popup&link=' . $url,
            'mailto:?subject=Share%20Buttons%20Demo&body=' . $url,
            $url
        ];
        return $list;
    }
}

if (!function_exists('forum__getIcons')) {
    function forum__getIcons()
    {
        return new ForumIconType();
    }
}

if (!function_exists('forum__getReports')) {
    function forum__getReports()
    {
        return new \Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadReport();
    }
}

if (!function_exists('forum__getLikes')) {
    function forum__getLikes()
    {
        return new ForumThreadLikes();
    }
}

if (!function_exists('forum__getEdits')) {
    function forum__getEdits()
    {
        return new \Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadEdits();
    }
}

if (!function_exists('forum__getAccess')) {
    function forum__getAccess()
    {
        return ForumAccess::orderByDesc('created_at');
    }
}


if (!function_exists('forum__userIsOnline')) {
    function forum__userIsOnline($user_id)
    {
        $access = ForumAccess::where('user_id', $user_id)->orderByDesc('created_at')->first()['created_at'];
        if ((bool)strtotime($access))
            return (strtotime(now()) - strtotime($access) < 180);
    }
}

if (!function_exists('forum__usesTranslator')) {
    function forum__usesTranslator($lAccess)
    {
        switch ($lAccess['uses']) {
            case "ForumController@index":
                return ucfirst(dateFormat($lAccess['created_at'])) . ' - ' . __('forum_arckene::activities.ForumController@index', ['route' => route('forum')]);
            case "ForumController@profile":
                return ucfirst(dateFormat($lAccess['created_at'])) . ' - ' . __('forum_arckene::activities.ForumController@profile');
            case "ForumController@profileHistory":
                return ucfirst(dateFormat($lAccess['created_at'])) . ' - ' . __('forum_arckene::activities.ForumController@profileHistory');
            case "ForumController@user":
                return ucfirst(dateFormat($lAccess['created_at'])) . ' - ' . __('forum_arckene::activities.ForumController@user', ['route' => route('forum.user', [json_decode($lAccess['parameters'])->slug, json_decode($lAccess['parameters'])->id]), 'name' => user(json_decode($lAccess['parameters'])->id)->pseudo]);
            case "ForumController@forum":
                return ucfirst(dateFormat($lAccess['created_at'])) . ' - ' . __('forum_arckene::activities.ForumController@forum', ['route' => route('forum.forum', [json_decode($lAccess['parameters'])->slug, json_decode($lAccess['parameters'])->id]), 'name' => ForumForum::find(json_decode($lAccess['parameters'])->id)['name']]);
            case "ThreadController@thread":
                return ucfirst(dateFormat($lAccess['created_at'])) . ' - ' . __('forum_arckene::activities.ThreadController@thread', ['route' => route('forum.thread', [json_decode($lAccess['parameters'])->slug, json_decode($lAccess['parameters'])->id]), 'name' => ForumThread::find(json_decode($lAccess['parameters'])->id)['name']]);
            case "ThreadController@new_thread" || "ThreadController@ajax_new_thread":
                return ucfirst(dateFormat($lAccess['created_at'])) . ' - ' . __('forum_arckene::activities.ThreadController@new_thread');
            case "ThreadController@edit_thread":
                return ucfirst(dateFormat($lAccess['created_at'])) . ' - ' . __('forum_arckene::activities.ThreadController@edit_thread');
            case "ThreadController@editHistory_thread":
                return ucfirst(dateFormat($lAccess['created_at'])) . ' - ' . __('forum_arckene::activities.ThreadController@editHistory_thread');
            default:
                return $lAccess['uses'];
        }
    }
}


if (!function_exists('forum__userSetting')) {
    function forum__userSetting($user_id, $parameter)
    {
        switch ($parameter) {
            case 'profile__show_birth':
                return ForumUsersSetting::find($user_id)['profile__show_birth'];
            case 'profile__show_birth_year':
                return ForumUsersSetting::find($user_id)['profile__show_birth_year'];
            case 'email__receive_onpost':
                return ForumUsersSetting::find($user_id)['email__receive_onpost'];
            case 'email__receive_onreply':
                return ForumUsersSetting::find($user_id)['email__receive_onreply'];
            case 'content__show_signature':
                return ForumUsersSetting::find($user_id)['content__show_signature'];
            case 'privacy__show_location':
                return ForumUsersSetting::find($user_id)['privacy__show_location'];
            case 'privacy__show_online':
                return ForumUsersSetting::find($user_id)['privacy__show_online'];
            case 'privacy__show_activity':
                return ForumUsersSetting::find($user_id)['privacy__show_activity'];
            case 'rank__show':
                return ForumUsersSetting::find($user_id)['rank__show'];
            case 'post__watched_forum':
                return ForumUsersNotification::find($user_id)['post__watched_forum'];
            case 'post__watched_thread':
                return ForumUsersNotification::find($user_id)['post__watched_thread'];
            case 'post_mention':
                return ForumUsersNotification::find($user_id)['post_mention'];
            case 'message_quoting':
                return ForumUsersNotification::find($user_id)['message_quoting'];
            case 'message_react':
                return ForumUsersNotification::find($user_id)['message_react'];
            case 'chat__private':
                return ForumUsersNotification::find($user_id)['chat__private'];
            case 'chat__poke':
                return ForumUsersNotification::find($user_id)['chat__poke'];
            case 'chat__message':
                return ForumUsersNotification::find($user_id)['chat__message'];
            case 'profile__react':
                return ForumUsersNotification::find($user_id)['profile__react'];
            case 'profile__comment':
                return ForumUsersNotification::find($user_id)['profile__comment'];
            case 'profile__comment_reply':
                return ForumUsersNotification::find($user_id)['profile__comment_reply'];
            case 'profile__friend_request':
                return ForumUsersNotification::find($user_id)['profile__friend_request'];
            case 'profile__trophy':
                return ForumUsersNotification::find($user_id)['profile__trophy'];
            default:
                return false;
        }
    }
}


if (!function_exists('color__edit')) {
    /**
     * @author Arckene - ThomasFar - http://thomasfar.fr
     * This function consists of changing a color according to 3 parameters.
     * @param string $color Hexadecimal color to edit
     * @param int $percent Percent of editing from 0 to 100
     * @param string $type Type of editing: lighten, darken or opacity
     * @return string|null Return the new color either in hexadecimal or in rgba.
     */
    function color__edit($color, $percent, $type)
    {
        $partition = explode('x', wordwrap(substr($color, 1, 6), 2, 'x', 3));
        unset($color);

        switch ($type) {
            case "lighten":
                $toReturn = '#';
                foreach ($partition as $p) {
                    $hex = round(hexdec($p) / 100 * (100 + $percent));
                    $hex = (($hex < 0) ? 0 : ($hex > 255)) ? 255 : $hex;
                    $toReturn .= substr('0' . dechex($hex), -2);
                }
                return $toReturn;
            case "darken":
                $toReturn = '#';
                foreach ($partition as $p) {
                    $hex = round(hexdec($p) / 100 * (100 - $percent));
                    $hex = (($hex < 0) ? 0 : ($hex > 255)) ? 255 : $hex;
                    $toReturn .= substr('0' . dechex($hex), -2);
                }
                return $toReturn;
            case "opacity":
                $toReturn = 'rgba(';
                foreach ($partition as $p) {
                    $toReturn .= hexdec($p) . ', ';
                }
                $toReturn .= $percent / 100;
                return $toReturn . ')';
        }
        return null;
    }
}

if (!function_exists('color__root')) {
    /**
     * This function consists in generating the root css file using the user parameters of the database.
     * @param int $interval_color Interval of lighten and darken, default: 15
     * @param int $interval_opacity Interval of opacity for each color variation, default: 25
     * @return string Return a text containing all the ":root".
     */
    function color__root($interval_color = 15, $interval_opacity = 25)
    {
        $forum_color = [
            'red' => "#ad1c1c",
            "blue" => "#1c51ad",
            "green" => "#20b024"
        ];

        $customization = ForumCustomization::first();

        $data = [
            'main' => $customization['color__main'],
            'second' => $customization['color__second'],
            'background' => $customization['color__background'],
            'red' => "#ad1c1c",
            "blue" => "#1c51ad",
            "green" => "#20b024"
        ];

        $toReturn = ":root {\n";

        foreach ($data as $key => $value) {
            $toReturn .= "\t--forum-{$key}: {$value};\n";
            for ($j = $interval_opacity; $j <= 100; $j += $interval_opacity) {
                $toReturn .= "\t--forum-{$key}-opacity-" . $j . ": " . color__edit($value, $j, 'opacity') . ";\n";
            }
            for ($i = $interval_color; $i <= 100; $i += $interval_color) {
                $toReturn .= "\t--forum-{$key}-lighten-" . $i . ": " . color__edit($value, $i, 'lighten') . ";\n";
                $toReturn .= "\t--forum-{$key}-darken-" . $i . ": " . color__edit($value, $i, 'darken') . ";\n";
                for ($j = $interval_opacity; $j <= 100; $j += $interval_opacity) {
                    $toReturn .= "\t--forum-{$key}-lighten-" . $i . "-opacity-" . $j . ": " . color__edit(color__edit($value, $i, 'lighten'), $j, 'opacity') . ";\n";
                    $toReturn .= "\t--forum-{$key}-darken-" . $i . "-opacity-" . $j . ": " . color__edit(color__edit($value, $i, 'darken'), $j, 'opacity') . ";\n";
                }
            }
        }

        $toReturn .= "}";
        return $toReturn;
    }
}