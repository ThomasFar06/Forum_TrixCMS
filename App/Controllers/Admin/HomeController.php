<?php

namespace Extensions\Plugins\Forum_arckene__421339390\App\Controllers\Admin;

use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumConfiguration;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumCustomization;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumPermissions;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumRanksRp;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumRanks;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadTags;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsers;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumWidgetShare;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumIconType;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumForum;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumUsersRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use App\System\Extensions\Plugin\Core\PluginController as AdminController;

class HomeController extends AdminController
{
    // Docs.TrixCMS.Eu
    public $admin = true;

    public function config()
    {
        $configuration = ForumConfiguration::first();
        $customization = ForumCustomization::first();
        $widgets = ForumWidgetShare::get();
        $icons = ForumIconType::get();

        View::share(compact('configuration', 'customization', 'widgets', 'icons'));
        return $this->view('admin.config', __('forum_arckene::admin.config_title'));
    }

    public function ajax_config(Request $request) {
        $inputs = $request->input();
        ForumConfiguration::first()->update([
            'forum' => (isset($inputs['forum']) && $inputs['forum'] == "on" ? 1 : 0),
            'theme' => (isset($inputs['theme']) && $inputs['theme'] == "on" ? 1 : 0),
            'widget__button' => (isset($inputs['widget__button']) && $inputs['widget__button'] == "on" ? 1 : 0),
            'button_link' => $inputs['button_link'],
            'button_name' => $inputs['button_name'],
            'widget__staff' => (isset($inputs['widget__staff']) && $inputs['widget__staff'] == "on" ? 1 : 0),
            'widget__online' => (isset($inputs['widget__online']) && $inputs['widget__online'] == "on" ? 1 : 0),
            'widget__discord' => (isset($inputs['widget__discord']) && $inputs['widget__discord'] == "on" ? 1 : 0),
            'discord' => $inputs['discord'],
            'widget__statistics' => (isset($inputs['widget__statistics']) && $inputs['widget__statistics'] == "on" ? 1 : 0),
            'widget__latest_post' => (isset($inputs['widget__latest_post']) && $inputs['widget__latest_post'] == "on" ? 1 : 0),
            'widget__share' => (isset($inputs['widget__share']) && $inputs['widget__share'] == "on" ? 1 : 0),
            'updated_at' => now()
        ]);
        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
    }

    public function ajax_custom(Request $request) {
        $inputs = $request->input();
        ForumCustomization::first()->update([
            'color__main' => $inputs['color__main'],
            'color__second' =>  $inputs['color__second'],
            'color__background' => $inputs['color__background'],
            'forum__description_tooltip' => (isset($inputs['forum__description_tooltip']) && $inputs['forum__description_tooltip'] == "on" ? 1 : 0),
            'forum__icon__default' => $inputs['forum__icon__default'],
            'user__profile__tooltip' => (isset($inputs['user__profile__tooltip']) && $inputs['user__profile__tooltip'] == "on" ? 1 : 0),
            'user__profile__avatar' => $inputs['user__profile__avatar'],
            'updated_at' => now()
        ]);
        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
    }

    public function ajax_share(Request $request) {
        $inputs = $request->input();
        ForumWidgetShare::insert([
            'name' => $inputs['name'],
            'icon_type' => $inputs['icon_type'],
            'icon' => $inputs['icon'],
            'color' => $inputs['color'],
            'created_at' => now()
        ]) ;
        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
    }

    public function ajax_icon(Request $request) {
        $inputs = $request->input();
        ForumIconType::insert([
            'name' => $inputs['name'],
            'website' => $inputs['website'],
            'format' => $inputs['format'],
            'import' => $inputs['import'],
            'type' => $inputs['type'],
            'created_at' => now()
        ]) ;
        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
    }

    public function xhr_share_delete($id) {
        ForumWidgetShare::findOrFail($id)->delete();
    }

    public function xhr_icon_delete($id) {
        ForumIconType::findOrFail($id)->delete();
    }

    public function forum()
    {
        $forum = ForumForum::orderBy('position')->get();
        $icons = ForumIconType::get();
        $ranks = ForumRanks::orderBy('power')->get();
        $tags = ForumThreadTags::get();


        View::share(compact('forum', 'icons', 'ranks', 'tags'));
        return $this->view('admin.forum', __('forum_arckene::admin.forum_title'));

    }

    public function ajax_forum(Request $request) {
        $inputs = $request->input();
        $last = ForumForum::orderByDesc('position')->first();
        ForumForum::insert([
            'name' => $inputs['name'],
            'description' => $inputs['description'],
            'size' => (int) $inputs['size'],
            'icon' => $inputs['icon'],
            'icon_type' => $inputs['icon_type'],
            'category' => 0,
            'write__rank_id' => $inputs['write__rank_id'],
            'watch_rank_id' => $inputs['watch_rank_id'],
            'position' => $last['position']+1,
            'created_at' => now()
        ]);
        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();

    }

    private function position($array) {
        $position = array();
        $i = 0;
        foreach ($array as $a_1) {
            $i++;
            $position += [$a_1['id'] => $i];
            if(isset($a_1['children'])) {
                foreach ($a_1['children'] as $a_2) {
                    $i++;
                    $position += [$a_2['id'] => $i];
                    if(isset($a_2['children'])) {
                        foreach ($a_2['children'] as $a_3) {
                            $i++;
                            $position += [$a_3['id'] => $i];
                            if(isset($a_3['children'])) {
                                foreach ($a_3['children'] as $a_4) {
                                    $i++;
                                    $position += [$a_4['id'] => $i];
                                    if(isset($a_4['children'])) {
                                        foreach ($a_4['children'] as $a_5) {
                                            $i++;
                                            $position += [$a_5['id'] => $i];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $position;
    }
	
    public function ajax_forum_nestable(Request $request) {
        $json = json_decode($request->input('json'), true);
        $position = self::position($json);
        $forum = (new ForumForum());
        foreach ($json as $j_1) {
            if(isset($j_1['children'])) {
                $forum->where('id', $j_1['id'])->update([
                    'category' => 1,
                    'forum_id' => 0
                ]);
                foreach ($j_1['children'] as $j_2) {
                    if(isset($j_2['children'])) {
                        $forum->where('id', $j_2['id'])->update([
                            'category' => 1,
                            'forum_id' => $j_1['id']
                        ]);
                        foreach ($j_2['children'] as $j_3) {
                            if(isset($j_3['children'])) {
                                $forum->where('id', $j_3['id'])->update([
                                    'category' => 1,
                                    'forum_id' => $j_2['id']
                                ]);
                                foreach ($j_3['children'] as $j_4) {
                                    if(isset($j_4['children'])) {
                                        $forum->where('id', $j_4['id'])->update([
                                            'category' => 1,
                                            'forum_id' => $j_3['id']
                                        ]);
                                        foreach ($j_4['children'] as $j_5) {
                                            if(isset($j_5['children'])) {
                                                $forum->where('id', $j_5['id'])->update([
                                                    'category' => 1,
                                                    'forum_id' => $j_4['id']
                                                ]);
                                            } else {
                                                $forum->where('id', $j_5['id'])->update([
                                                    'category' => 0,
                                                    'forum_id' => $j_4['id']
                                                ]);
                                            }
                                        }
                                    } else {
                                        $forum->where('id', $j_4['id'])->update([
                                            'category' => 0,
                                            'forum_id' => $j_3['id']
                                        ]);
                                    }
                                }
                            } else {
                                $forum->where('id', $j_3['id'])->update([
                                    'category' => 0,
                                    'forum_id' => $j_2['id']
                                ]);
                            }
                        }
                    } else {
                        $forum->where('id', $j_2['id'])->update([
                            'category' => 0,
                            'forum_id' => $j_1['id']
                        ]);
                    }
                }
            } else {
                $forum->where('id', $j_1['id'])->update([
                    'category' => 0,
                    'forum_id' => 0
                ]);
            }
        }
        foreach ($forum->get() as $f) {
            $forum->where('id', $f['id'])->update([
                'position' => $position[$f['id']],
                'updated_at' => now()
            ]);
        }
    }

    public function ajax_forum_get_edit(Request $request) {
        return json_encode(ForumForum::findOrFail($request->input('id')), true);
    }

    public function ajax_forum_edit(Request $request) {
        $inputs = $request->input();
        ForumForum::findOrFail($inputs['id'])->update([
            'name' => $inputs['name'],
            'description' => $inputs['description'],
            'size' => (int) $inputs['size'],
            'icon' => $inputs['icon'],
            'icon_type' => $inputs['icon_type'],
            'write__rank_id' => 1,
            'watch_rank_id' => 1,
            'updated_at' => now()
        ]);
        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
    }

    public function ajax_forum_tags(Request $request){
        $inputs = $request->input();
        ForumThreadTags::insert([
            'rank_id' => json_encode($inputs['tags_rank']),
            'thread_id' => json_encode($inputs['tags_forum']),
            'name' => $inputs['tags_name'],
            'text_color' => $inputs['tags_color'],
            'background_color' => $inputs['tags_background'],
            'created_at' => now()
        ]);
        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
    }

    public function xhr_forum_delete($id) {
        ForumForum::findOrFail($id)->delete();
    }

    public function ranks()
    {
        $permissions = ForumPermissions::get();
        $ranks = ForumRanks::orderBy('power')->get();

        View::share(compact('permissions', 'ranks'));
        return $this->view("admin.ranks", __('forum_arckene::admin.Ranks.title'));

    }

    public function generatePermissions() {
        foreach (ForumPermissions::get() as $fp) {
            ForumPermissions::findOrFail($fp['id'])->delete();
        }

        $data = [
            1 => "FORUM_ACCESS",
            2 => "THREAD_POST",
            3 => "THREAD_POST_FILE",
            4 => "THREAD_EDIT_OWN",
            5 => "THREAD_DELETE_OWN",
            6 => "THREAD_SEE",
            7 => "THREAD_LIKE",
            8 => "THREAD_REPLY",
            9 => "THREAD_REPORT",
            10 => "THREAD_SHARE",
            11 => "THREAD_BOOKMARK",
            12 => "THREAD_MOVE_ALL",
            13 => "THREAD_EDIT_OTHER",
            14 => "THREAD_EDIT_HISTORY",
            15 => "THREAD_DELETE_OTHER",
            16 => "THREAD_SEE_DELETE",
            17 => "THREAD_PIN",
            18 => "THREAD_LOCK",
            19 => "THREAD_ARCHIVE",
            20 => "THREAD_DELETE",
            21 => "THREAD_SEE_ARCHIVE",
            22 => "REPORT_LIST",
            23 => "REPORT_ACTION",
            24 => "MODERATOR_TOOLS",
            25 => "USER_STATUS_OWN",
            26 => "USER_STATUS_OTHER",
            27 => "USER_EDIT_OWN",
            28 => "USER_EDIT_OTHER",
            29 => "USER_BAN",
            30 => "USER_BAN_HISTORY"
        ];

        foreach ($data as $key => $value) {
            ForumPermissions::insert([
                'id' => $key,
                'name' => $value,
                'created_at' => now()
            ]);
        }
    }

    public function ajax_add_rank(Request $request) {
        $inputs = $request->input();
        $ranks = (new ForumRanks);
        $rp = (new ForumRanksRp());
        $ranks::insert([
            'name' => $inputs['name'],
            'background' => $inputs['background'],
            'staff' => (isset($inputs['staff']) && $inputs['staff'] == "on" ? 1 : 0),
            'color' => $inputs['color'],
            'created_at' => now()
        ]);
        $last = $ranks::orderByDesc('created_at')->first();
        $id = $last['id'];
        foreach ($inputs['perms'] as $key => $v) {
            $rp::insert([
                'rank_id' => $id,
                'permission_id' => $key,
                'action' => $v,
                'created_at' => now()
            ]);
        }
        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
    }

    public function ajax_ranks_nestable(Request $request) {
        $json = json_decode($request->input('json'), true);
        $ranks = (new ForumRanks);
        $position = self::position($json);
        foreach ($ranks->get() as $r) {
            $ranks->where('id', $r['id'])->update([
                'power' => $position[$r['id']],
                'updated_at' => now()
            ]);
        }
    }

    public function xhr_rank_delete($id) {
        ForumRanks::findOrFail($id)->delete();
        ForumRanksRp::where('rank_id', $id)->delete();
    }

    public function xhr_rank_default($id) {
        ForumRanks::where('default', 1)->update([
            'default' => null,
            'updated_at' => now()
        ]);
        ForumRanks::findOrFail($id)->update([
            'default' => 1,
            'updated_at' => now()
        ]);
    }

    public function ajax_forum_get_rank(Request $request) {
        return json_encode(ForumRanks::findOrFail($request->input('id')), true);
    }

    public function ajax_forum_get_rp(Request $request) {
        return json_encode(ForumRanksRp::where('rank_id', $request->input('id'))->get(), true);
    }

    public function ajax_edit_rank(Request $request) {
        $inputs = $request->input();
        $ranks = (new ForumRanks);
        $rp = (new ForumRanksRp());
        $ranks::findOrFail($inputs['edit_id'])->update([
            'name' => $inputs['edit_name'],
            'background' => $inputs['edit_background'],
            'staff' => (isset($inputs['edit_staff']) && $inputs['edit_staff'] == "on" ? 1 : 0),
            'color' => $inputs['edit_color'],
            'updated_at' => now()
        ]);
        foreach ($inputs['edit_perms'] as $key => $v) {
            if(count($rp::where('rank_id', $inputs['edit_id'])->where('permission_id', $key)->get()) > 0) {
                $rp::where('rank_id', $inputs['edit_id'])->where('permission_id', $key)->update([
                    'action' => $v,
                    'updated_at' => now()
                ]);
            } else {
                $rp::insert([
                    'rank_id' => $inputs['edit_id'],
                    'permission_id' => $key,
                    'action' => $v,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

        }
        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
    }

    public function users()
    {
        $users = ForumUsers::get();
        View::share(compact('users'));
        return $this->view("admin.users", __('forum_arckene::admin.Users.title'));
    }

    public function user($id) {
        $user = ForumUsers::find($id);
        $ranks = ForumRanks::orderBy('power')->get();
        View::share(compact('user', 'ranks'));
        return $this->view("admin.user", __('forum_arckene::admin.User.title', ['pseudo' => user($id)->pseudo]));
    }


    public function user__xml__unban($id) {
        ForumUsers::find($id)->update([
            'banned' => 0,
            'updated_at' => now()
        ]);
    }
    public function user__xml__ban($id) {
        ForumUsers::find($id)->update([
            'banned' => 1,
            'updated_at' => now()
        ]);
    }
    public function user__xml__avatarReset($id) {
        ForumUsers::find($id)->update([
            'avatar' => null,
            'updated_at' => now()
        ]);
    }
    public function user__ajax__rank(Request $request) {
        $inputs = $request->input();
        $user_ranks = (new ForumUsersRank);

        $user_ranks->where('id', $inputs['id'])->delete();
        foreach ($inputs['ranks'] as $rank) {
            $user_ranks->insert([
                'id' => $inputs['id'],
                'rank_id' => $rank,
                'created_at' => now()
            ]);
        }

        return response()->json(['alert' => 'success', 'message' => __("messages.Updated")])->getContent();
    }

    public function xhr_tag_delete($id) {
        ForumThreadTags::findOrFail($id)->delete();
    }

}
