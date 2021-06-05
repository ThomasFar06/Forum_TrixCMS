<?php

namespace Extensions\Plugins\Forum_arckene__421339390\App\Controllers;


use App\System\Extensions\Plugin\Core\PluginController as Controller;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumConfiguration;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumCustomization;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumForum;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumIconType;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumPermissions;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThread;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadLikes;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadReport;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadTags;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsers;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersFriend;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersNotification;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersSetting;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Imagick;
use ImagickDraw;


class ForumController extends Controller
{
    // Docs.TrixCMS.Eu

    private $configuration;
    private $customization;
    private $icons;
    private $forums;
    private $threads;
    private $likes;
    private $reports;
    private $friends;

    /**
     * @var ForumFunction
     */
    private $function;

    public function __construct()
    {
        $this->function = new ForumFunction();

        $this->configuration = ForumConfiguration::first();
        $this->customization = ForumCustomization::first();
        $this->icons = ForumIconType::get();
        $this->forums = ForumForum::orderBy('position')->get();
        $this->threads = (new ForumThread());
        $this->likes = (new ForumThreadLikes());
        $this->reports = (new ForumThreadReport());
        $this->friends = (new ForumUsersFriend());

        $configuration = $this->configuration;
        $customization = $this->customization;
        $icons = $this->icons;
        $forums = $this->forums;
        $threads = $this->threads;
        $likes = $this->likes;
        $reports = $this->reports;
        $friends = $this->friends;

        if ($this->configuration['forum'] != 1)
            return redirect()->action('HomeController@home')->send();

        View::share(compact('configuration', 'customization', 'icons', 'forums', 'threads', 'likes', 'reports', 'friends'));
    }

    public function jsonHome()
    {
        $toReturn = array();
        $i = 0;
        foreach ($this->forums->where('forum_id', null) as $f) {
            if (forum__canSee($f)) {
                $toReturn[$i]['id'] = $f['id'];
                $toReturn[$i]['icon'] = iconify($f);
                $toReturn[$i]['name'] = $f['name'];
                $toReturn[$i]['description'] = $f['description'];
                $toReturn[$i]['size'] = $f['size'];
                $toReturn[$i]['canWrite'] = forum__canPost($f['id']);
                $toReturn[$i]['route'] = route('forum.forum', [Str::slug($f['name']), $f['id']]);
                if (count($this->forums->where('forum_id', $f['id']))) {
                    $children = [];
                    $j = 0;
                    foreach ($this->forums->where('forum_id', $f['id']) as $c) {
                        $children[$j]['id'] = $c['id'];
                        $children[$j]['icon'] = iconify($c);
                        $children[$j]['name'] = $c['name'];
                        $children[$j]['description'] = $c['description'];
                        $children[$j]['size'] = $c['size'];
                        $children[$j]['route'] = route('forum.forum', [Str::slug($c['name']), $c['id']]);
                        $children[$j]['count']['threads'] = threadCount($c['id']);
                        $children[$j]['count']['answers'] = messageCount($c['id']);
                        $children[$j]['count']['sub_forums'] = forumCount($c['id']);
                        $children[$j]['canWrite'] = forum__canPost($c['id']);

                        $last_thread = getLastThread($c['id']);
                        if ($last_thread) {
                            $children[$j]['last']['name'] = $last_thread['name'];
                            $children[$j]['last']['route'] = route('forum.thread', [Str::slug($last_thread['name']), $last_thread['id']]);
                            $children[$j]['last']['author'] = __('forum_arckene::thread.Last.info', ['author' => '<a href="' . route('forum.user', [user($last_thread['author_id'])->pseudo, $last_thread['author_id']]) . '">' . user($last_thread['author_id'])->pseudo . '</a>', $last_thread['author_id'], 'date' => dateFormat($last_thread['created_at'])]);
                        } else {
                            $children[$j]['last'] = null;
                        }
                        $j++;
                    }
                    $toReturn[$i]['children'] = $children;
                } else {
                    $toReturn[$i]['children'] = null;
                }
            }
            $i++;
        }
        return response()->json($toReturn);
    }

    public function jsonForum($id)
    {
        $toReturn = array();
        $i = 0;
        foreach ($this->forums->where('id', $id) as $f) {
            if (forum__canSee($f)) {
                $toReturn[$i]['id'] = $f['id'];
                $toReturn[$i]['icon'] = iconify($f);
                $toReturn[$i]['name'] = $f['name'];
                $toReturn[$i]['description'] = $f['description'];
                $toReturn[$i]['canWrite'] = forum__canPost($f['id']);
                $toReturn[$i]['route'] = route('forum.forum', [Str::slug($f['name']), $f['id']]);
                if (count($this->forums->where('forum_id', $f['id']))) {
                    $children = [];
                    $j = 0;
                    foreach ($this->forums->where('forum_id', $f['id']) as $c) {
                        if (forum__canSee($c)) {
                            $children[$j]['id'] = $c['id'];
                            $children[$j]['icon'] = iconify($c);
                            $children[$j]['name'] = $c['name'];
                            $children[$j]['description'] = $c['description'];
                            $children[$j]['route'] = route('forum.forum', [Str::slug($c['name']), $c['id']]);
                            $children[$j]['count']['threads'] = threadCount($c['id']);
                            $children[$j]['count']['answers'] = messageCount($c['id']);
                            $children[$j]['count']['sub_forums'] = forumCount($c['id']);
                            $children[$j]['canWrite'] = forum__canPost($c['id']);

                            $last_thread = getLastThread($c['id']);
                            if ($last_thread) {
                                $children[$j]['last']['name'] = $last_thread['name'];
                                $children[$j]['last']['route'] = route('forum.thread', [Str::slug($last_thread['name']), $last_thread['id']]);
                                $children[$j]['last']['author'] = __('forum_arckene::thread.Last.info', ['author' => '<a href="' . route('forum.user', [user($last_thread['author_id'])->pseudo, $last_thread['author_id']]) . '">' . user($last_thread['author_id'])->pseudo . '</a>', $last_thread['author_id'], 'date' => dateFormat($last_thread['created_at'])]);
                            } else {
                                $children[$j]['last'] = null;
                            }
                            $j++;
                        }
                    }
                    $toReturn[$i]['children'] = $children;
                } else {
                    $toReturn[$i]['children'] = null;
                }
                $i++;
            }
        }
        return response()->json($toReturn);
    }

    public function jsonForumThread($id, int $page = null, int $paginate = 10)
    {
        if (!$page)
            $page = 1;

        $toReturn = array();
        if (forum__canSee($this->forums->where('id', $id)->first())) {
            foreach ($this->forums->where('id', $id) as $f) {
                $toReturn['name'] = $f['name'];
                $last = getLastThread($f['id']);
                if ($last) {
                    $toReturn['last'] = trans_choice('forum_arckene::public/global.Format.last_thead_number', $this->threads->where('forum_id', $id)->count()) .
                        __('forum_arckene::public/global.Format.last_thread_last', ['name' => $this->function->format__link(route('forum.thread', [Str::slug($last['name']), $last['id']]), $last['name']), 'author' => lcfirst($this->function->format__author($last['author_id'], $last['created_at']))]);
                } else {
                    $toReturn['last'] = null;
                }
                $toReturn['canWrite'] = forum__canPost($f['id']);
                $toReturn['route'] = forum__canPost($f['id']);
                $toReturn['isPaginate'] = $this->threads->orderByDesc('created_at')->where('forum_id', $id)->count() > $paginate;
                $toReturn['totalPage'] = ceil($this->threads->orderByDesc('created_at')->where('forum_id', $id)->count() / $paginate);
                $toReturn['currentPage'] = $page;
            }

            $i = 0;
            foreach (ForumThread::orderByDesc('pin')->orderByDesc('updated_at')->where('forum_id', $id)->skip(($page - 1) * $paginate)->limit($paginate)->get() as $t) {
                $toReturn['threads'][$i]['id'] = $t['id'];
                if (!empty($t['tags']) || !is_null($t['tags'])) {
                    foreach (json_decode($t['tags']) as $tag) {
                        $toReturn['threads'][$i]['tags'][] = $this->function->format__tag(ForumThreadTags::find($tag));
                    }
                } else {
                    $toReturn['threads'][$i]['tags'] = null;
                }
                $toReturn['threads'][$i]['avatar'] = userInfo($t['author_id'], 'avatar');
                $toReturn['threads'][$i]['name'] = $t['name'];
                $toReturn['threads'][$i]['route'] = route('forum.thread', [Str::slug($t['name']), $t['id']]);
                $toReturn['threads'][$i]['author'] = $this->function->format__author($t['author_id'], $t['created_at']);

                $toReturn['threads'][$i]['state']['followed'] = false;
                $toReturn['threads'][$i]['state']['pinned'] = $t['pin'] ? true : false;
                $toReturn['threads'][$i]['state']['locked'] = $t['lock'] ? true : false;
                $toReturn['threads'][$i]['state']['archived'] = $t['archive'] ? true : false;
                $toReturn['threads'][$i]['state']['deleted'] = $t['delete'] ? true : false;

                $toReturn['threads'][$i]['answer'] = count($this->threads->where('thread_id', $t['id'])->get());

                $last = $this->threads->orderByDesc('created_at')->where('thread_id', $t['id'])->orWhere('id', $t['id'])->first();
                $toReturn['threads'][$i]['last']['name'] = $this->function->format__link(route('forum.user', [user($last['author_id'])->pseudo, $last['author_id']]), user($last['author_id'])->pseudo);
                $toReturn['threads'][$i]['last']['id'] = $last['id'];
                $toReturn['threads'][$i]['last']['date'] = $this->function->format__date($last['created_at']);
                $i++;
            }
            return response()->json($toReturn);
        }
    }


    public function index()
    {

        if ($this->configuration['theme'] == 1) {
            return setViewForTheme("421339390::public.index");
        } else {
            return $this->view('public.content.index', __('forum_arckene::forum.title'));
        }
    }

    public function profile()
    {
        return $this->view('public.content.profile', __('forum_arckene::forum.profile', ['slug' => user()->pseudo]));
    }

    public function profileHistory($page = 1)
    {
        $histories = forum__getAccess()->where('user_id', user()->id)->paginate(50);

        View::share(compact('histories'));

        return $this->view('public.content.profile_history', __('forum_arckene::forum.profile', ['slug' => user()->pseudo]));
    }

    public function user($slug, $id)
    {
        $user = ForumUsers::find($id);
        View::share(compact('user'));
        return $this->view('public.content.user', __('forum_arckene::forum.profile', ['slug' => user($id)->pseudo]));
    }


    public function forum($slug, $id)
    {

        $forum = ForumForum::find($id);
        $thread = ForumThread::orderByDesc('pin')->orderByDesc('created_at')->where('forum_id', $forum['id'])->where('delete', null)->orWhere('delete', 0)->paginate(20);
        if ($slug != slugify($forum['name'])) {
            return redirect()->route('forum.forum', [slugify($forum['name']), $forum['id']]);
        }

        $last_thread = ForumThread::orderByDesc('created_at')->where('forum_id', $forum['id'])->where('delete', null)->orWhere('delete', 0)->first();


        View::share(compact('forum', 'thread', 'last_thread'));

        if ($this->configuration['theme'] == 1) {
            return setViewForTheme("421339390::public.view");
        } else {
            return $this->view('public.content.forum', __('forum_arckene::forum.sub_title', ['slug' => $forum['name']]));
        }
    }


    public function defaultProfile($slug, $id)
    {
        $text = ucfirst(substr($slug, 0, 1));

        $draw = new ImagickDraw();
        $draw->setStrokeColor($this->customization['color__main']);
        $draw->setFillColor($this->customization['color__main']);
        $draw->setFontSize(150);

        $draw->annotation(100, 200, $text);

        $imagick = new Imagick();
        $imagick->newImage(300, 300, $this->customization['color__second']);
        $imagick->setImageFormat("png");
        $imagick->drawImage($draw);

        header("Content-Type: image/png");
        return $imagick->getImageBlob();
    }

    public function likeMessage($id)
    {
        $thread = ForumThread::find($id);
        if (isLiked($thread)) {
            ForumThreadLikes::where('user_id', user()->id)->where('thread_id', $id)->delete();

            if (forum__userSetting($thread['author_id'], 'message_react'))
                $this->model()->instancy('UserNotifications')->addNotification($thread['author_id'], 'Le joueur ' . user()->pseudo . ' a supprimé son j\'aime le message ' . $thread['id']);

        } else {
            ForumThreadLikes::insert([
                'user_id' => user()->id,
                'thread_id' => $id,
                'created_at' => now()
            ]);

            if (forum__userSetting($thread['author_id'], 'message_react'))
                $this->model()->instancy('UserNotifications')->addNotification($thread['author_id'], 'Le joueur ' . user()->pseudo . ' a aimé le message ' . $thread['id']);

        }
    }

    public function followUser($id)
    {
        if (!tx_model()->user->userExist($id))
            exit;

        if ($id == user()->id)
            exit;

        $isFollow = forum__getFollow()->where('id', user()->id)->where('friend_id', $id)->count();
        if ($isFollow > 0) {
            forum__getFollow()->where('id', user()->id)->where('friend_id', $id)->delete();
        } else {
            forum__getFollow()->insert([
                'id' => user()->id,
                'friend_id' => $id,
                'created_at' => now()
            ]);
        }
    }

    public function moderateAction($action, $id)
    {
        if (forumPermission('MODERATOR_TOOLS')) {
            if ($action == "pin") {
                if (forumPermission('THREAD_PIN')) {
                    ForumThread::find($id)->update([
                        'pin' => !ForumThread::find($id)['pin'],
                        'updated_at' => now()
                    ]);
                }
            } else if ($action == "lock") {
                if (forumPermission('THREAD_LOCK')) {
                    ForumThread::find($id)->update([
                        'lock' => !ForumThread::find($id)['lock'],
                        'updated_at' => now()
                    ]);
                }
            } else if ($action == "archive") {
                if (forumPermission('THREAD_ARCHIVE')) {
                    ForumThread::find($id)->update([
                        'archive' => !ForumThread::find($id)['archive'],
                        'updated_at' => now()
                    ]);
                }
            } else if ($action == "delete") {
                if (forumPermission('THREAD_DELETE_OTHER')) {
                    ForumThread::find($id)->update([
                        'delete' => 1,
                        'updated_at' => now()
                    ]);
                }
            } else if ($action == "ban") {
                if (forumPermission('USER_BAN')) {
                    ForumUsers::find($id)->update([
                        'banned' => 1,
                        'updated_at' => now()
                    ]);
                }
            } else if ($action == "archiveReport") {
                if (forumPermission('REPORT_ACTION')) {
                    ForumThreadReport::find($id)->update([
                        'archive' => !ForumThreadReport::find($id)['archive'],
                        'updated_at' => now()
                    ]);
                }
            }

        }
    }

    public function updateThreadLocation(Request $request)
    {
        $inputs = $request->input();
        if (forumPermission('MODERATOR_TOOLS')) {
            if (forumPermission('THREAD_MOVE_ALL')) {
                ForumThread::find($inputs['thread_id'])->update([
                    'forum_id' => $inputs['select_forum_data'],
                    'updated_at' => now()
                ]);
                return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
            }
        }
    }

    public function perms($id)
    {
        $perms = ForumPermissions::get();
        $toReturn = [];
        $i = 0;
        foreach ($perms as $p) {
            $i++;
            $toReturn[$p['name']] = forumPermission($p['name']);
        }
        dd($toReturn);
    }

    public function ajax_notification(Request $request)
    {
        $inputs = $request->input();
        if (forumPermission('USER_EDIT_OWN')) {
            ForumUsersNotification::updateOrInsert([
                'id' => user()->id
            ], [
                'post__watched_forum' => (isset($inputs['post__watched_forum']) && $inputs['post__watched_forum'] == "on" ? 1 : 0),
                'post__watched_thread' => (isset($inputs['post__watched_thread']) && $inputs['post__watched_thread'] == "on" ? 1 : 0),
                'post_mention' => (isset($inputs['post_mention']) && $inputs['post_mention'] == "on" ? 1 : 0),
                'message_quoting' => (isset($inputs['message_quoting']) && $inputs['message_quoting'] == "on" ? 1 : 0),
                'message_react' => (isset($inputs['message_react']) && $inputs['message_react'] == "on" ? 1 : 0),
                'chat__private' => (isset($inputs['chat__private']) && $inputs['chat__private'] == "on" ? 1 : 0),
                'chat__poke' => (isset($inputs['chat__poke']) && $inputs['chat__poke'] == "on" ? 1 : 0),
                'chat__message' => (isset($inputs['chat__message']) && $inputs['chat__message'] == "on" ? 1 : 0),
                'profile__react' => (isset($inputs['profile__react']) && $inputs['profile__react'] == "on" ? 1 : 0),
                'profile__comment' => (isset($inputs['profile__comment']) && $inputs['profile__comment'] == "on" ? 1 : 0),
                'profile__comment_reply' => (isset($inputs['profile__comment_reply']) && $inputs['profile__comment_reply'] == "on" ? 1 : 0),
                'profile__friend_request' => (isset($inputs['profile__friend_request']) && $inputs['profile__friend_request'] == "on" ? 1 : 0),
                'profile__trophy' => (isset($inputs['profile__trophy']) && $inputs['profile__trophy'] == "on" ? 1 : 0),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return response()->json(['success' => true, 'message' => __('forum_arckene::users.Notification.validation')]);
        } else {
            return response()->json(['success' => false, 'message' => [__('forum_arckene::permissions.LACK')]]);
        }
    }

    public function ajax_setting(Request $request)
    {
        $inputs = $request->input();
        if (forumPermission('USER_EDIT_OWN')) {


            if ($inputs['avatar'] != url('/public/forum_arckene/avatar/' . md5(Str::slug(user()->pseudo)))) {
                if (!in_array(pathinfo($inputs['avatar'])['extension'], ['png', 'jpeg', 'jpg', 'gif']))
                    return response()->json(['success' => false, 'message' => ["L'extension du fichier doit etre soit png, soit jpg, soit jpeg ou sois gif, pas " . pathinfo($inputs['avatar'])['extension']]]);

                if (getimagesize($inputs['avatar'])[0] - getimagesize($inputs['avatar'])[1] != 0)
                    return response()->json(['success' => false, 'message' => ["Et c pa kré "]]);

                File::copy($inputs['avatar'], public_path('forum_arckene/avatar/' . md5(Str::slug(user()->pseudo))));

                if (File::size(public_path('forum_arckene/avatar/' . md5(Str::slug(user()->pseudo)))) >= 1000000) {
                    File::delete(File::size(public_path('forum_arckene/avatar/' . md5(Str::slug(user()->pseudo)))));
                    return response()->json(['success' => false, 'message' => ["Et c tro lour poto "]]);
                }
            }


            ForumUsers::unguard();

            ForumUsers::findOrFail(user()->id)->update([
                'avatar_type' => 0,
                'avatar' => url('/public/forum_arckene/avatar/' . md5(Str::slug(user()->pseudo))),
                'title' => htmlspecialchars($inputs['title']),
                'birth' => ((bool)strtotime($inputs['birth'])) ? $inputs['birth'] : null,
                'location' => htmlspecialchars($inputs['location']),
                'website' => htmlspecialchars($inputs['website']),
                'about' => htmlspecialchars(($inputs['about'])),
                'signature' => $inputs['editor'],
                'updated_at' => now()
            ]);

            ForumUsers::reguard();


            ForumUsersSetting::unguard();

            ForumUsersSetting::findOrFail(user()->id)->update([
                'timezone' => now()->timezone,
                'profile__show_birth' => (isset($inputs['profile__show_birth']) && $inputs['profile__show_birth'] == "on" ? 1 : 0),
                'profile__show_birth_year' => (isset($inputs['profile__show_birth_year']) && $inputs['profile__show_birth_year'] == "on" ? 1 : 0),
                'email__receive_onpost' => (isset($inputs['email__receive_onpost']) && $inputs['email__receive_onpost'] == "on" ? 1 : 0),
                'email__receive_onreply' => (isset($inputs['email__receive_onreply']) && $inputs['email__receive_onreply'] == "on" ? 1 : 0),
                'content__show_signature' => (isset($inputs['content__show_signature']) && $inputs['content__show_signature'] == "on" ? 1 : 0),
                'privacy__show_location' => (isset($inputs['privacy__show_location']) && $inputs['privacy__show_location'] == "on" ? 1 : 0),
                'privacy__show_online' => (isset($inputs['privacy__show_online']) && $inputs['privacy__show_online'] == "on" ? 1 : 0),
                'rank__show' => (isset($inputs['rank__show']) && $inputs['rank__show'] == "on" ? 1 : 0),
                'updated_at' => now()
            ]);

            ForumUsersSetting::reguard();

            return response()->json(['success' => true, 'message' => __('forum_arckene::users.Notification.validation')]);
        } else {
            return response()->json(['success' => false, 'message' => [__('forum_arckene::permissions.LACK')]]);
        }
    }

    public function ajax_avatar(Request $request)
    {
        $img = $request->file('img');

        if ($img == null)
            return response()->json(['response' => 2])->getContent();

        if ($img->getSize() >= 1000000)
            return 'NOP';

        if (getimagesize($img)[0] - getimagesize($img)[1] != 0)
            return 'NOP';

        if (in_array($img->getClientOriginalExtension(), ['png', 'jpeg', 'jpg', 'gif']))
            $img->move(public_path('forum_arckene/avatar'), md5(Str::slug(user()->pseudo)));

        $url = url('/public/forum_arckene/avatar/' . md5(Str::slug(user()->pseudo)));

        ForumUsers::unguard();

        ForumUsers::findOrFail(user()->id)->update([
            'avatar_type' => 0,
            'avatar' => e($url)
        ]);

        ForumUsers::reguard();

        return response()->json(['response' => get_storage_file($url), 'data' => $url])->getContent();
    }


}