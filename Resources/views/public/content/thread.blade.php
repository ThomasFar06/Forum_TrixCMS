@PluginElement('public/elements/head')
<div class="flex-wrapper">
    <div class="flex-container">
        <div class="flex-breadcrumb">
            @php($breadcrumb = breadcrumb($forum))
            @php($i = 0)
            @foreach($breadcrumb as $b)
                @php($i++)
                <a href="{{ $b['route'] }}">{!! $b['name'] !!}</a>
            @endforeach
            <a href="" class="active">{!! $thread['name'] !!}</a>
        </div>
        <div class="flex-info" style="display: flex">
            <div class="flex-size-5">
                <h1>
                    @if(!empty($thread['tags']) || !is_null($thread['tags']))
                        @foreach(json_decode($thread['tags']) as $tt)
                            {!! forum__tags($tt) !!}
                        @endforeach
                    @endif
                    {!! $thread['name'] !!}</h1>
                <p>{!! __('forum_arckene::forum.thread_info', ['forum' => '<a href="#">'.$forum['name'].'</a>', 'author' => '<a href="#">'.user($thread['author_id'])->pseudo.'</a>', 'date' => dateFormat($thread['created_at'])]) !!}</p>
            </div>
            <div class="flex-size-1">
                <span class="float-right">
                    @PluginElement('public/elements/moderator')
                </span>
            </div>
        </div>
        {!! $replies->links('421339390::public.elements.pagination') !!}
        @php($i=0)
        @foreach($replies->where('delete', '<>', 1) as $reply)
            @php($i++)
            <div class="forum-thread" data-id="{{ $reply['id'] }}">
                <div class="flex-profile">
                    <div class="forum-profile">
                        <img src="{{ userInfo($reply['author_id'], 'avatar') }}" alt="{{user($reply['author_id'])->pseudo}}">
                        <div class="forum-profile-username">
                            <a href="{{ route('forum.user', [slugify(user($reply['author_id'])->pseudo), user($reply['author_id'])->id]) }}">{{user($reply['author_id'])->pseudo}}</a>
                        </div>
                        <p class="forum-profile-title">{{ userInfo($reply['author_id'], 'title') }}</p>
                        @if(forum__userSetting(user($reply['author_id'])->id, 'rank__show'))
                            @php($userRanks = userRanks($reply['author_id']))
                            @foreach($userRanks as $rank)
                                <div class="flex-rank" style="background: {{ $rank['background'] }}; color: {{ $rank['color'] }}">
                                    {{ rankName($rank['name']) }}
                                </div>
                            @endforeach
                        @endif
                        <hr>
                        <div class="stat">
                            {!! __('forum_arckene::thread.User.date', ['attr' => (user($reply['author_id'])->created_at)->format('d/m/Y')]) !!}
                        </div>
                        <div class="stat">
                            {!! __('forum_arckene::thread.User.messages', ['attr' => userInfo($reply['author_id'], 'messages')]) !!}
                        </div>
                        <div class="stat">
                            {!! __('forum_arckene::thread.User.likes', ['attr' => userInfo($reply['author_id'], 'likes')]) !!}
                        </div>
                    </div>
                </div>
                <div class="flex-content">
                    @if(forumPermission('MODERATOR_TOOLS'))
                        <div class="moderator-bar">
                            <div class="float-left">
                                <div class="flex-action">
                                    @if(forumPermission('REPORT_LIST'))
                                        <span>{{ __('forum_arckene::forum.Moderate.report', ['number' => forum__getReports()->where(function ($q) {
                                    return $q->where('archive', 0)->orWhere('archive', null);
                                    })->where('thread_id', $reply['id'])->count()]) }}</span>
                                    @endif
                                    <span>{{ __('forum_arckene::forum.Moderate.like', ['number' => forum__getLikes()->where('thread_id', $reply['id'])->count()]) }}</span>
                                    <span>{{ __('forum_arckene::forum.Moderate.id', ['number' => $reply['id']]) }}</span>
                                </div>
                            </div>
                            <span class="float-right">
                                @if(forumPermission('THREAD_EDIT_HISTORY'))
                                    <a href="{{ route('forum.threadEditHistory', [slugify($thread['name']), $reply['id']]) }}" class="flex-btn main-background">{{ __('forum_arckene::forum.Moderate.edit_history') }}</a>
                                @endif
                                @if(forumPermission('REPORT_LIST'))
                                    <a href="javascript:void(0)" class="flex-btn main-background reports_tools" data-thid="{{ $reply['id'] }}">{{ __('forum_arckene::forum.Moderate.report_tools') }}</a>
                                @endif
                            </span>
                        </div>
                    @endif

                    <div style="margin-bottom: 1rem">
                        {!! threadMessage($reply['message'], $reply['author_id']) !!}
                    </div>

                    <div class="action">
                        <div class="float-left">

                            @if(forum__getEdits()->where('thread_id', $reply['id'])->orderByDesc('created_at')->count() > 0)
                                @php($edit = forum__getEdits()->where('thread_id', $reply['id'])->orderByDesc('created_at')->first())
                                <div>
                                    {!! __('forum_arckene::thread.Edit.edited', ['date' => dateFormat($edit['created_at']), 'author' => '<a href="'. route('forum.user', [slugify(user($edit['author_id'])->pseudo), user($edit['author_id'])->id]) .'">'.user($edit['author_id'])->pseudo.'</a>']) !!}
                                </div>
                            @endif

                            <div class="flex-action">
                                <span>
                                    {{ user($reply['author_id'])->pseudo }}, {{ dateFormat($reply['created_at']) }}
                                </span>
                                @if(forumPermission('THREAD_REPORT'))
                                    <span>
                                        <a href="javascript:void(0)" class="report-button" data-id="{{ $reply['id'] }}" data-thid="{{ key_exists("page", $_GET) ? $i + ($_GET['page']-1)*10 : $i  }}">
                                            <i class="fas fa-exclamation-triangle"></i> {{ __('forum_arckene::forum.Reply.report') }}
                                        </a>
                                    </span>
                                @endif
                                @if(tx_auth()->isLogin())

                                    @if(($reply['author_id'] == user()->id && forumPermission('THREAD_EDIT_OWN')) || forumPermission('THREAD_EDIT_OTHER'))
                                        <span>
                                            <a href="{{ route('forum.threadEdit', [slugify($thread['name']), $reply['id']]) }}"><i class="fas fa-edit"></i> {{ __('forum_arckene::forum.Reply.edit') }}</a>
                                        </span>
                                    @endif
                                    @if(($reply['author_id'] == user()->id && forumPermission('THREAD_DELETE_OWN')) || forumPermission('THREAD_DELETE_OTHER'))
                                        <span>
                                            <a href="javascript:void(0)" class="delete-button" data-id="{{ $reply['id'] }}" data-thid="{{ key_exists("page", $_GET) ? $i + ($_GET['page']-1)*10 : $i  }}">
                                                <i class="fas fa-trash"></i> {{ __('forum_arckene::forum.Reply.delete') }}
                                            </a>
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="float-right">
                            <div class="flex-action">
                                <span><a href="#">#{{ key_exists("page", $_GET) ? $i + ($_GET['page']-1)*10 : $i  }}</a></span>
                                @if(forumPermission('THREAD_REPLY'))
                                    <span>
                                        <a href="#reply" onclick="reply({{ $reply['id'] }})">
                                            <i class="fas fa-reply"></i> {{ __('forum_arckene::forum.Reply.action') }}
                                        </a>
                                    </span>
                                @endif
                                @if(forumPermission('THREAD_LIKE'))
                                    <span>
                                        <a class="like @if(isLiked($reply)) liked @endif" style="color: tomato" onclick="like('{{ route('forum.like.message', [$reply['id']]) }}', $(this))">
                                            @if(isLiked($reply))
                                                <i class="fas fa-heart"></i>
                                            @else
                                                <i class="far fa-heart"></i>
                                            @endif
                                        </a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        {!! $replies->links('421339390::public.elements.pagination') !!}
        @if(forumPermission('THREAD_REPLY'))
            <div class="forum-thread" id="reply">
                <div class="flex-profile">
                    <div class="forum-profile">
                        <img src="{{ userInfo(user()->id, 'avatar') }}" alt="{{user(user()->id)->pseudo}}">
                    </div>
                </div>
                <div class="flex-content">
                    <div id="preview" style="display: none"></div>

                    <form class="sendAjaxRequest reloadAfter" ajax-action="{{ route('forum.post.threadReply') }}">
                        <div class="ajaxResponse" style="margin-bottom: 1rem"></div>
                        <input type="hidden" name="thread_id" value="{{ $thread['id'] }}">
                        <div style="display: flow-root">
                            <div id="editor" style="min-height: 10rem;"></div>
                        </div>
                        <span class="char-counter-editor"><span id="number">0</span> {!! __('forum_arckene::validator.char') !!}<span id="plural"></span></span>

                        <span class="float-right">
                        <button type="submit" class="flex-btn main-background"><i class="fas fa-reply"></i> {{ __('forum_arckene::forum.Reply.submit') }}</button>
                        <button onclick="preview()" type="button" class="flex-btn main-background"><i class="fas fa-eye"></i> {{ __('forum_arckene::forum.Reply.preview') }}</button>
                    </span>
                    </form>
                </div>
            </div>
        @endif

        @if(tx_auth()->isLogin())
            @PluginElement('public/elements/report')
            @PluginElement('public/elements/reports')
            @PluginElement('public/elements/delete')
        @endif

    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" integrity="sha512-LhZScx/m/WBAAHyiPnM+5hcsmCMjDDOgOqoT9wyIcs/QUPm6YxVNGZjQG5iP8dhWMWQAcUUTE3BkshtrlGbv2Q==" crossorigin="anonymous" />



<script>
    $('.quotesFullShow').click(function (e) {
        let content = $(this).prev();
        let time = 250;
        console.log(content.height());
        if (content.height() === 88) {
            let curHeight = content.height(), // Get Default Height
                autoHeight = content.css('height', 'auto').height(); // Get Auto Height
            content.height(curHeight); // Reset to Default Height
            content.stop().animate({height: autoHeight+16}, time); // Animate to Auto Height
            setTimeout(function () {
                content.css('height', 'auto')
            }, time+50);
        } else {
            content.stop().animate({height: '120'}, time);
        }
    });

    function like(route, element) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', route);
        xhr.send(null);
        element.addClass('disabled-link');
        if(element.hasClass('liked')) {
            element.toggleClass('liked');
            element.html('<i class="far fa-heart"></i>')
        } else {
            element.toggleClass('liked');
            element.html('<i class="fas fa-heart"></i>')
        }
        setTimeout(function () {
            element.removeClass('disabled-link');
        }, 2500);
    }

    function reply(id) {
        let stockage =  $('#editor').trumbowyg('html');
        $('#editor').trumbowyg('html', stockage + "[QUOTE message_id="+id+"][/QUOTE]");
    }

</script>

<script src="@PluginAssets('js/request.js')"></script>


@PluginElement('public/elements/trumbowyg')
<script src="@PluginAssets('js/trumbowyg.js')"></script>

@PluginElement('public/elements/global/footer')
