@if($configuration['widget__latest_post'])
    <div class="flex-sidebar-element">
        @foreach(forum__getThreads()->where('thread_id', null)->latest()->take((forum__getThreads()->latest()->count() < 3) ? forum__getThreads()->latest()->count() : 3)->get() as $t)
            <div class="last_post">
                <span class="flex-icon">
                        <img src="{{ userInfo($t['author_id'], 'avatar') }}" alt="{{user($t['author_id'])->pseudo}}">
                </span>
                <span class="flex-name">
                    <a href="{{ route('forum.thread', [slugify($t['name']), $t['id']]) }}"><span>{{ $t['name'] }}</span></a>
                    <p>
                        Par {{ user($t['author_id'])->pseudo }}, {{ dateFormat($t['created_at']) }}
                    </p>
                </span>
            </div>
        @endforeach
    </div>
@endif