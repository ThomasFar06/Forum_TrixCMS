<?php

namespace Extensions\Plugins\Forum_arckene__421339390\App\Controllers;

use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumConfiguration;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumCustomization;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumForum;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumIconType;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThread;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadEdits;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumThreadReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

use App\System\Extensions\Plugin\Core\PluginController as Controller;

class ThreadController extends Controller
{
    public function __construct()
    {
        $configuration = ForumConfiguration::first();
        $customization = ForumCustomization::first();
        $icons = ForumIconType::get();

        if ($configuration['forum'] != 1)
            return redirect()->action('HomeController@home')->send();

        View::share(compact('configuration', 'customization', 'icons'));
    }

    public function thread($slug, $id) {
        $thread = ForumThread::find($id);
        $forum = ForumForum::find($thread['forum_id']);
        $forums = ForumForum::get();
        $reports = new ForumThreadReport;

        if($thread['delete'] == 1)
            return redirect()->route('forum.forum', [slugify($forum['name']), $forum['id']]);

        if ($slug != slugify($thread['name'])) {
            return redirect()->route('forum.thread', [slugify($thread['name']), $thread['id']]);
        }
        $replies = ForumThread::orderBy('created_at')->where('thread_id', $id)->orWhere('id', $id)->paginate(10);

        View::share(compact('forums','forum', 'thread', 'replies', 'reports'));

        return $this->view('public.content.thread', __('forum_arckene::forum.thread_title', ['slug' => $thread['name']]));

    }

    public function new_thread($slug, $id)
    {
        $forum = ForumForum::find($id);

        if($forum['forum_id'] == 0)
            return redirect()->route('forum');

        if ($slug != slugify($forum['name']))
            return redirect()->route('forum.threadNew', [slugify($forum['name']), $forum['id']]);

        if(forumPermission('THREAD_POST') && forum__canPost($forum['id'])) {
            View::share(compact('forum'));

            return $this->view('public.content.thread_new', __('forum_arckene::thread.New.title_for_layout'));
        } else {
            return redirect()->route('forum.forum', [slugify($forum['name']), $forum['id']]);
        }


    }

    public function edit_thread($slug, $id)
    {
        $thread = ForumThread::find($id);

        $parent = ForumThread::find($thread['thread_id']);
        if (is_null($thread['thread_id']))
            $parent = $thread;

        if ((forumPermission('THREAD_EDIT_OWN') && user()->id == $thread['author_id']) || forumPermission('THREAD_EDIT_OTHER')) {

            $forum = ForumForum::find($parent['forum_id']);

            if (empty(slugify($parent['name'])))
                return redirect()->route('forum.thread', [slugify($parent['name']), $parent['id']]);

            if ($slug != slugify($parent['name']))
                return redirect()->route('forum.threadEdit', [slugify($parent['name']), $thread['id']]);

            View::share(compact('forum', 'thread', 'parent'));
            return $this->view('public.content.thread_edit', __('forum_arckene::thread.Edit.title_for_layout'));
        } else {
            return redirect()->route('forum.thread', [slugify($parent['name']), $parent['id']]);
        }
    }

    public function ajax_new_thread(Request $request)
    {
        $inputs = $request->input();

        $forum = ForumForum::find($inputs['id']);

        if(forumPermission('THREAD_POST') && forum__canPost($forum['id'])) {
            $validator = Validator::make($inputs, [
                "id" => "required",
                "name" => "required|min:15|max:50",
                "editor" => 'required|min:150',
            ], [
                "required" => __('forum_arckene::validator.thread.required'),
                "min" => __('forum_arckene::validator.thread.min'),
                "max" => __('forum_arckene::validator.thread.max'),
            ]);

            $validator->setAttributeNames([
                "id" => __('forum_arckene::validator.thread.id'),
                "editor" => __('forum_arckene::validator.thread.editor'),
                "name" => __('forum_arckene::validator.thread.name'),
            ]);

            if ($validator->fails())
                return response()->json(['success' => false, 'message' => $validator->errors()->all()]);


            ForumThread::insert([
                "name" => $inputs['name'],
                "message" => $inputs['editor'],
                "forum_id" => $forum['id'],
                "tags" => isset($inputs['tags']) ? json_encode($inputs['tags']) : null,
                "author_id" => user()->id,
                "created_at" => now()
            ]);


            return response()->json(['success' => true, 'message' => __('forum_arckene::validator.thread.success_new'), 'parameter' => ForumThread::orderByDesc('created_at')->where('forum_id', $forum['id'])->where('author_id', user()->id)->first()['id']]);
        } else {
            return response()->json(['success' => false, 'message' => [__('forum_arckene::permissions.LACK')]]);
        }
    }

    public function ajax_edit_thread(Request $request)
    {
        $inputs = $request->input();

        $thread = ForumThread::find($inputs['id']);

        if ((forumPermission('THREAD_EDIT_OWN') && user()->id == $thread['author_id']) || forumPermission('THREAD_EDIT_OTHER')) {
            $validator = Validator::make($inputs, [
                "id" => 'required',
                "name" => "required|min:15|max:50",
                "editor" => 'required|min:150',
            ], [
                "required" => __('forum_arckene::validator.thread.required'),
                "min" => __('forum_arckene::validator.thread.min'),
                "max" => __('forum_arckene::validator.thread.max'),
            ]);

            $validator->setAttributeNames([
                "editor" => __('forum_arckene::validator.thread.editor'),
                "name" => __('forum_arckene::validator.thread.name'),
                "id" => __('forum_arckene::validator.thread.id'),
            ]);

            if ($validator->fails())
                return response()->json(['success' => false, 'message' => $validator->errors()->all()]);

            if (is_null($thread['thread_id'])) {
                ForumThreadEdits::insert([
                    "author_id" => user()->id,
                    "thread_id" => $inputs['id'],
                    "name" => $thread['name'],
                    "message" => $thread['editor'],
                    "show" => true,
                    "created_at" => now()
                ]);	
                ForumThread::findOrFail($inputs['id'])->update([
                    "name" => $inputs['name'],
                    "message" => $inputs['editor'],
                    "tags" => isset($inputs['tags']) ? json_encode($inputs['tags']) : null,
                    "updated_at" => now()
                ]);
            } else {
                ForumThreadEdits::insert([
                    "author_id" => user()->id,
                    "thread_id" => $inputs['id'],
                    "message" => $thread['editor'],
                    "show" => true,
                    "created_at" => now()
                ]);

                ForumThread::findOrFail($inputs['id'])->update([
                    "message" => $inputs['editor'],
                    "updated_at" => now()
                ]);
            }

            return response()->json(['success' => true, 'message' => __('forum_arckene::validator.thread.success_edit')]);
        } else {
            return response()->json(['success' => false, 'message' => [__('forum_arckene::permissions.LACK')]]);
        }
    }


    public function ajax_report_thread(Request $request) {
        $inputs = $request->input();
        if(forumPermission('THREAD_REPORT')) {
            ForumThreadReport::insert([
                'author_id' => user()->id,
                'thread_id' => $inputs['thread_id_report'],
                'reason' => $inputs['report_reason'],
                'created_at' => now()
            ]);
            return response()->json(['success' => true, 'message' => __('forum_arckene::validator.thread.report')]);
        } else {
            return response()->json(['success' => false, 'message' => [__('forum_arckene::permissions.LACK')]]);
        }
    }

    public function ajax_reply_thread(Request $request) {
        $inputs = $request->input();
        $id = user()->id;
        if(forumPermission('THREAD_REPLY')) {

            $validator = Validator::make($inputs, [
                "thread_id" => 'required',
                "editor" => 'required|min:100',
            ], [
                "required" => __('forum_arckene::validator.thread.required'),
                "min" => __('forum_arckene::validator.thread.min'),
                "max" => __('forum_arckene::validator.thread.max'),
            ]);

            $validator->setAttributeNames([
                "editor" => __('forum_arckene::validator.thread.editor'),
                "thread_id" => __('forum_arckene::validator.thread.id'),
            ]);

            if ($validator->fails())
                return response()->json(['success' => false, 'message' => $validator->errors()->all()]);

            ForumThread::insert([
                "author_id" => $id,
                "thread_id" => $inputs['thread_id'],
                "message" => $inputs['editor'],
                "created_at" => now()
            ]);

            $threads = forum__getThreads();

            //NOTIFICATION IF ANSWER
            $quotes = array();
            preg_match_all('/\[QUOTE message_id=(\d*)\]\[\/QUOTE\]/', $inputs['editor'], $quotes);
            if (key_exists(0, $quotes[0])) {
                for ($i = 0; $i < count($quotes[0]); $i++) {
                    if ($threads->find($quotes[1][$i]))
                        if (forum__userSetting($quotes[1][$i], 'message_quoting'))
                            $this->model()->instancy('UserNotifications')->addNotification($threads->find($quotes[1][$i])['author_id'], 'Le joueur ' . $id . ' a répondu a votre message dans ' . $inputs['thread_id']);
                }
            }
            //NOTIFICATION IF AT
            $mention = array();
            preg_match_all('/@[^#]+#(\d+)/', $inputs['editor'], $mention);
            if (key_exists(0, $mention[0])) {
                for ($i = 0; $i < count($mention[0]); $i++) {
                    if (tx_model()->user->userExist((int)$mention[1][$i]))
                        if(forum__userSetting($mention[1][$i], 'post_mention'))
                            $this->model()->instancy('UserNotifications')->addNotification($mention[1][$i], 'Le joueur ' . $id . ' vous a mentionnez dans le message ' . $inputs['thread_id']);
                }
            }


            $page = (int)ceil((count(ForumThread::where('thread_id', $inputs['thread_id'])->get()) + 0.1) / 10);
            return response()->json(['success' => true, 'message' => __('forum_arckene::validator.thread.reply')]);
        } else {
            return response()->json(['success' => false, 'message' => [__('forum_arckene::permissions.LACK')]]);
        }
    }

    public function xml_delete_thread($id)
    {
        $thread = ForumThread::find($id);

        if ((forumPermission('THREAD_DELETE_OWN') && user()->id == $thread['author_id']) || forumPermission('THREAD_DELETE_OTHER')) {
            $thread->update([
                'delete' => 1,
                'updated_at' => now()
            ]);
        }
    }

    public function ajax_get_report(Request $request) {
        $reports = ForumThreadReport::where(function ($q) {
            return $q->where('archive', 0)->orWhere('archive', null);
        })->where('thread_id', $request->input('id'))->get();

        $reports_edited = [];
        $i=0;
        foreach ($reports as $report) {
            $reports_edited[$i]['id'] = $report['id'];
            $reports_edited[$i]['reason'] = htmlspecialchars($report['reason']);
            $reports_edited[$i]['info'] = __('forum_arckene::forum.by_date', ['user' => user($report['author_id'])->pseudo, 'date' => dateFormat($report['created_at'])]);
            $i++;
        }

        return json_encode($reports_edited, true);
    }

    public function editHistory_thread($slug, $id)
    {
        $thread = ForumThread::find($id);

        $parent = ForumThread::find($thread['thread_id']);
        if (is_null($thread['thread_id']))
            $parent = $thread;

        if ((forumPermission('THREAD_EDIT_OWN') && user()->id == $thread['author_id']) || forumPermission('THREAD_EDIT_OTHER')) {

            $forum = ForumForum::find($parent['forum_id']);

            if (empty(slugify($parent['name'])))
                return redirect()->route('forum.thread', [slugify($parent['name']), $parent['id']]);

            if ($slug != slugify($parent['name']))
                return redirect()->route('forum.threadEdit', [slugify($parent['name']), $thread['id']]);

            View::share(compact('forum', 'thread', 'parent'));
            return $this->view('public.content.thread_edit_history', __('forum_arckene::thread.Edit.title_for_layout'));
        } else {
            return redirect()->route('forum.thread', [slugify($parent['name']), $parent['id']]);
        }
    }
}
