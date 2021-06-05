@PluginElement('public/elements/head')
<div class="flex-wrapper">
    <div class="flex-container m-flex">
        <div class="flex-size-5 flex-row">
            @foreach($forums->where('forum_id', 0) as $f)
                @if(forum__canSee($f))
                    <div class="flex-card reducible {{ forumasize($f) }}">
                        <div class="flex-card-header">
                        <span class="flex-icon">
                            {!! iconify($f) !!}
                        </span>
                            <span class="flex-name">
                            <a href="{{ route('forum.forum', [slugify($f['name']), $f['id']]) }}" @if($customization['forum__description_tooltip'] == 1 && $f['description'] != null) data-tooltip="{{ $f['description'] }}" data-tooltip-location="right" @endif><span>{{ $f['name'] }}</span></a>
                        </span>
                            <span class="flex-reducer">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                        </div>
                        <div class="flex-card-body">
                            @php($i=0)
                            @forelse($forums->where('forum_id', $f['id']) as $sf)
                                @php($i++)
                                <div class="sub-forum @if($i % 2 == 0) pair @endif {{ forumasize($sf) }}">

                                    <div class="m-flex">

                                        <div class="flex-size-3">
                                        <span class="flex-icon">
                                            {!! iconify($sf) !!}
                                        </span>
                                            <span class="flex-name">
                                            <a href="{{ route('forum.forum', [slugify($sf['name']), $sf['id']]) }}" @if($customization['forum__description_tooltip'] == 1 && $sf['description'] != null) data-tooltip="{{ $sf['description'] }}" data-tooltip-location="right" @endif><span>{{ $sf['name'] }}</span></a>
                                            <p>
                                                @if($sf['size'] == 1)
                                                    <span class="forum-counter">{{ Str::limit(__('forum_arckene::forum.threads'), 4, '.') }}: {{ threadCount($sf['id']) }}</span>
                                                    <span class="forum-counter">{{ Str::limit(__('forum_arckene::forum.messages'), 4, '.') }}: {{ messageCount($sf['id']) }}</span>
                                                    <span class="forum-counter">{{ Str::limit(__('forum_arckene::forum.sub_forums'), 4, '.') }}: {{ forumCount($sf['id']) }}</span>
                                                @else
                                                    <span class="forum-counter">
                                                    {{ __('forum_arckene::forum.threads') }}: {{ threadCount($sf['id']) }}
                                                </span>
                                                    <span class="forum-counter">
                                                    {{ __('forum_arckene::forum.messages') }}: {{ messageCount($sf['id']) }}
                                                </span>
                                                    <span class="forum-counter">
                                                    {{ __('forum_arckene::forum.sub_forums') }}: {{ forumCount($sf['id']) }}
                                                </span>
                                                @endif
                                            </p>
                                        </span>
                                        </div>
                                        @php($last_thread = getLastThread($sf['id']))
                                        @if($last_thread)
                                            <div class="flex-size-1">
                                                <div class="forum-last-post">
                                                    <div class="forum-message">
                                                        {!! __('forum_arckene::thread.Last.name', ['name' => '<a href="'.route('forum.thread', [slugify($last_thread['name']), $last_thread['id']]).'">'.$last_thread['name'].'</a>']) !!}
                                                    </div>
                                                    <div class="forum-author">
                                                        {!! __('forum_arckene::thread.Last.info', ['author' => '<a href="'.route('forum.user', [user($last_thread['author_id'])->pseudo, $last_thread['author_id']]).'">'.user($last_thread['author_id'])->pseudo.'</a>', $last_thread['author_id'], 'date' => dateFormat($last_thread['created_at'])]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex-size-1">
                                                <div class="forum-last-post">
                                                    <div class="forum-message">
                                                        Aucune discussion
                                                    </div>
                                                    <div class="forum-author">
                                                        Ajouter une disccusion
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @empty
                                <div class="empty-forum">{{ __('forum_arckene::forum.empty_forum') }}</div>
                            @endforelse
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="flex-size-1">
            @PluginElement('public/sidebar/sidebar')
        </div>
    </div>
</div>

<script>
    $('.flex-card-header .flex-reducer i').click(function (){
        let body = $(this).parent().parent().next();
        if(body.css('display') !== 'none') {
            $(this).addClass('fa-chevron-up');
            $(this).removeClass('fa-chevron-down');
            $(this).parent().parent().next().slideUp();
        } else {
            $(this).removeClass('fa-chevron-up');
            $(this).addClass('fa-chevron-down');
            $(this).parent().parent().next().slideDown();
        }
    });
</script>