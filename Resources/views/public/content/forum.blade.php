@PluginElement('public/elements/head')

<div class="flex-wrapper">
    <div class="flex-container">
        <div class="flex-breadcrumb">
            @php($breadcrumb = breadcrumb($forum))
            @php($i = 0)
            @foreach($breadcrumb as $b)
                @php($i++)
                <a href="{{ $b['route'] }}" class="@if($i == count($breadcrumb)) active @endif">{!! $b['name'] !!}</a>
            @endforeach
        </div>

        @if(count($forums->where('forum_id', $forum['id'])) > 0)
            <div class="flex-card">
                <div class="flex-card-header">
                <span class="flex-icon">
                    {!! iconify($forum) !!}
                </span>
                    <span class="flex-name">
                    <a href="{{ route('forum.forum', [slugify($forum['name']), $forum['id']]) }}"
                       @if($customization['forum__description_tooltip'] == 1 && $forum['description'] != null) data-tooltip="{{ $forum['description'] }}" data-tooltip-location="right" @endif><span>{{ $forum['name'] }}</span></a>
                </span>
                </div>
                <div class="flex-card-body">
                    @php($i=0)
                    @foreach($forums->where('forum_id', $forum['id']) as $sf)
                        @php($i++)
                        <div class="sub-forum @if($i % 2 == 0) pair @endif">

                            <div class="m-flex">

                                <div class="flex-size-3">
                                    <span class="flex-icon">
                                        {!! iconify($sf) !!}
                                    </span>
                                    <span class="flex-name">
                                        <a href="{{ route('forum.forum', [slugify($sf['name']), $sf['id']]) }}" @if($customization['forum__description_tooltip'] == 1 && $sf['description'] != null) data-tooltip="{{ $sf['description'] }}" data-tooltip-location="right" @endif><span>{{ $sf['name'] }}</span></a>
                                        <p>
                                            <span class="forum-counter">
                                                {{ __('forum_arckene::forum.threads') }}: {{ threadCount($sf['id']) }}
                                            </span>
                                            <span class="forum-counter">
                                                {{ __('forum_arckene::forum.messages') }}: {{ messageCount($sf['id']) }}
                                            </span>
                                            <span class="forum-counter">
                                                {{ __('forum_arckene::forum.sub_forums') }}: {{ forumCount($sf['id']) }}
                                            </span>
                                        </p>
                                    </span>
                                </div>
                                @php($last_threads = getLastThread($sf['id']))
                                @if($last_threads)
                                    <div class="flex-size-1">
                                        <div class="forum-last-post">
                                            <div class="forum-message">
                                                {!! __('forum_arckene::thread.Last.name', ['name' => '<a href="'.route('forum.thread', [slugify($last_threads['name']), $last_threads['id']]).'">'.$last_threads['name'].'</a>']) !!}
                                            </div>
                                            <div class="forum-author">
                                                <a href="{{ route('forum.user', [user($last_threads['author_id'])->pseudo, $last_threads['author_id']]) }}">{!! __('forum_arckene::thread.Last.info', ['author' => user($last_threads['author_id'])->pseudo, 'date' => dateFormat($last_threads['created_at'])]) !!}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($forum['forum_id'] != 0)
            <div class="flex-info" style="display: flex">
                <div class="flex-size-5">
                    <h1>{{ $forum['name'] }}</h1>
                    @if(count($thread) > 0)
                        <p>{!! trans_choice('forum_arckene::forum.forum_desc', count($thread), ['thread' => '<a href="#">'.$last_thread['name'].'</a>', 'author' => '<a href="#">'.user($last_thread['author_id'])->pseudo.'</a>', 'date' => dateFormat($last_thread['created_at'])]) !!}</p>
                    @else
                        <p>{!! trans_choice('forum_arckene::forum.forum_desc', count($thread), ['thread' => '', 'author' => '', 'date' => '']) !!}</p>
                    @endif
                </div>
                @if(forumPermission('THREAD_POST') && forum__canPost($forum['id']))
                    <div class="flex-size-1">
                        <span class="float-right">
                            <a href="{{ route('forum.threadNew', [slugify($forum['name']), $forum['id']]) }}" class="flex-round-btn" data-tooltip="Nouvelle discussion" data-tooltip-location="left">
                                <i class="fas fa-edit"></i>
                            </a>
                        </span>
                    </div>
                @endif
            </div>
            {!! $thread->links('421339390::public.elements.pagination') !!}
            @if(count($thread) > 0)
                <div class="flex-card-table">
                    <div class="forum-threads w-100">

                        <div class="forum-threads-header sm-flex">
                            <div class="flex-size-thread sm-hide">{{ __('forum_arckene::forum.forum_thread_table_title') }}</div>
                            <div class="flex-size-1 sm-hide" style="margin-left: -4rem;">{{ __('forum_arckene::forum.forum_thread_table_answer') }}</div>
                            <div class="flex-size-2 sm-hide">{{ __('forum_arckene::forum.forum_thread_table_last') }}</div>
                            <div class="sm-show">{{ __('forum_arckene::forum.threads') }}</div>
                        </div>

                    </div>
                    <div class="forum-threads-body">
                        @php($i=0)
                        @foreach($thread as $t)
                            @php($i++)
                            <div class="threads-line @if($i % 2 == 0) pair @endif sm-flex">
                                <div class="flex-size-thread" style="height: 62px">
                                        <span class="flex-icon">
                                            <img src="{{ userInfo($t['author_id'], 'avatar') }}" alt="{{$t['author_id']}}">
                                        </span>
                                    <span class="flex-name">
                                            <a href="{{ route('forum.thread', [slugify($t['name']), $t['id']]) }}">
                                                <span>
                                                    @if(!empty($t['tags']) || !is_null($t['tags']))
                                                        @foreach(json_decode($t['tags']) as $tt)
                                                            {!! forum__tags($tt) !!}
                                                        @endforeach
                                                    @endif
                                                    {{ $t['name'] }}
                                                </span>
                                            </a>
                                            <p>
                                                Par <a href="{{ route('forum.user', [user($t['author_id'])->pseudo, $t['author_id']]) }}">{{ user($t['author_id'])->pseudo }}</a>, {{ dateFormat($t['created_at']) }}
                                            </p>
                                        </span>
                                    <span class="float-right" style="padding: 0.45rem;">
                                            @if($t['pin'])
                                            <span class="state pin">
                                                    <i class="fas fa-thumbtack"></i>
                                                </span>
                                        @endif
                                        @if($t['lock'])
                                            <span class="state lock">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                        @endif
                                        @if($t['archive'])
                                            <span class="state archive">
                                                    <i class="fas fa-archive"></i>
                                                </span>
                                        @endif
                                        </span>
                                </div>
                                <div class="flex-size-1 sm-hide" style="padding: 1.05rem;">
                                    {{ count(forum__getThreads()->where('thread_id', $t['id'])->get()) }}
                                </div>
                                <div class="flex-size-2">

                                    <div class="forum-last-post">
                                        <div class="forum-message">
                                            <a href="{{ route('forum.user', [user(forum__getThreads()->orderByDesc('created_at')->where('thread_id', $t['id'])->orWhere('id', $t['id'])->first()['author_id'])->pseudo, forum__getThreads()->orderByDesc('created_at')->where('thread_id', $t['id'])->orWhere('id', $t['id'])->first()['author_id']]) }}">{{ user(forum__getThreads()->orderByDesc('created_at')->where('thread_id', $t['id'])->orWhere('id', $t['id'])->first()['author_id'])->pseudo }}</a>
                                        </div>
                                        <div class="forum-author">
                                            {{ ucfirst(dateFormat(forum__getThreads()->orderByDesc('created_at')->where('thread_id', $t['id'])->orWhere('id', $t['id'])->first()['created_at'])) }}
                                        </div>
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>
                    <div class="forum-threads-footer sm-flex">

                        <div class="flex-size-thread sm-hide">{{ __('forum_arckene::forum.forum_thread_table_title') }}</div>
                        <div class="flex-size-1 sm-hide" style="margin-left: -4rem;">{{ __('forum_arckene::forum.forum_thread_table_answer') }}</div>
                        <div class="flex-size-2 sm-hide">{{ __('forum_arckene::forum.forum_thread_table_last') }}</div>
                        <div class="sm-show">{{ __('forum_arckene::forum.threads') }}</div>

                    </div>

                </div>

                    <style>

                    </style>
                </div>
            @endif
            {!! $thread->links('421339390::public.elements.pagination') !!}
        @endif
    </div>
</div>