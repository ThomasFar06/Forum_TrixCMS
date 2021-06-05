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
            <a href="#" >{{ __('forum_arckene::thread.History.breadcrumb') }}</a>
            <a href="" class="active">{!! $parent['name'] !!}</a>
        </div>

        <a href="{{ route('forum.thread', [slugify($parent['name']), $parent['id']]) }}" class="main-background flex-btn w-100" style="margin: 0.3rem 0.3rem 1rem;">
            {{ __('forum_arckene::thread.History.back') }}
        </a>

        @forelse(forum__getEdits()->where('thread_id', $thread['id'])->get() as $e)
            <div class="forum-thread" data-id="{{ $e['id'] }}">
                <div class="flex-profile">
                    <div class="forum-profile">
                        <img src="{{ userInfo($e['author_id'], 'avatar') }}" alt="{{user($e['author_id'])->pseudo}}">
                        <div class="forum-profile-username">
                            <a href="{{ route('forum.user', [slugify(user($e['author_id'])->pseudo), user($e['author_id'])->id]) }}">{{user($e['author_id'])->pseudo}}</a>
                        </div>
                        <p class="forum-profile-title">{{ userInfo($e['author_id'], 'title') }}</p>
                    </div>
                </div>
                <div class="flex-content">

                    @if(!is_null($e['name']))
                        <h4><span style="font-weight: 300">{{ __('forum_arckene::thread.History.name') }}</span> {{ $e['name'] }}</h4>
                    @endif
                    <div style="margin-bottom: 1rem">
                        {!! threadMessage($e['message']) !!}
                    </div>
                </div>
            </div>
        @empty
            <div class="forum-thread">
                {{ __('forum_arckene::thread.History.no') }}
            </div>
        @endforelse

    </div>
</div>
@PluginElement('public/elements/global/footer')
