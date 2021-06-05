@PluginElement('public/elements/head')
@php
    use Carbon\Carbon;
@endphp
<div class="flex-wrapper">
    <div class="flex-container">
        <div class="flex-breadcrumb">
            <a href="{{ action('HomeController@home') }}" class=""><i class="fas fa-home"></i></a>
            <a href="{{ route('forum') }}" class="">Forum</a>
            <a href="{{ route('forum') }}" class="active">{{ user($user['id'])->pseudo }}</a>
        </div>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

        @if(userInfo($user['id'], 'banned'))
            <div class="text-center">
                <div class="flex-btn main-background" style="background: #ab1b1b">
                    {{ __('forum_arckene::users.User.banned') }}
                </div>
            </div>
        @else
            <div class="forum-profile">

                <div class="profile-head">

                    <div class="img-profile">
                        <img src="{{ userInfo($user['id'], 'avatar') }}" alt="{{ user($user['id'])->pseudo }}">
                    </div>

                    <div class="info-profile">
                        <div>
                            <h1>{{ user($user['id'])->pseudo }}</h1>
                        </div>

                        <div>
                            {{ userInfo($user['id'], 'title') }} @if(userInfo($user['id'], 'staff')) - {{ __('forum_arckene::users.User.staff') }} @endif
                        </div>

                        @if(userInfo($user['id'], 'banned'))
                            <div>
                                <div class="flex-rank" style="background: #ab1b1b; color: #ecf0f1;width: fit-content;padding: 0 1.5rem; display: inline-block">
                                    {{ __('forum_arckene::users.User.banned') }}
                                </div>
                            </div>
                        @endif

                        @if(forum__userSetting(user($user['id'])->id, 'rank__show'))
                            <div>

                                @php($userRanks = userRanks($user['id']))
                                @foreach($userRanks as $rank)
                                    <div class="flex-rank" style="background: {{ $rank['background'] }}; color: {{ $rank['color'] }};width: fit-content;padding: 0 1.5rem; display: inline-block">
                                        {{ rankName($rank['name']) }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div>
                            {{ __('forum_arckene::forum.User.register', ['date' => dateFormat(user($user['id'])->created_at)]) }}.
                        </div>
                        <div>
                            {{ __('forum_arckene::users.User.last_activity') }} {!! forum__usesTranslator(forum__getAccess()->where('user_id', $user['id'])->first()) !!}
                        </div>

                        <span class="action">
                        @if(tx_auth()->isLogin())
                                @if(user()->id != $user['id'])
                                    @php($isFollow = forum__getFollow()->where('id', user()->id)->where('friend_id', $user['id'])->count())
                                    <a href="javascript:void(0)" onclick="follow('{{ route('forum.follow.user', [$user['id']]) }}', $(this))" class="flex-btn main-background @if($isFollow > 0) liked @endif" style="padding: 0.5rem 1rem">
                                    @if($isFollow > 0)
                                            <i class="fas fa-share-alt"></i> {{ __('forum_arckene::users.User.unfollow') }}
                                        @else
                                            <i class="fas fa-share-alt"></i> {{ __('forum_arckene::users.User.follow') }}
                                        @endif
                                </a>
                                    <script>
                                function follow(route, element) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('GET', route);
                                    xhr.send(null);
                                    element.addClass('disabled-link');
                                    if(element.hasClass('liked')) {
                                        element.toggleClass('liked');
                                        element.html('<i class="fas fa-share-alt"></i> {{ __('forum_arckene::users.User.follow') }}')
                                    } else {
                                        element.toggleClass('liked');
                                        element.html('<i class="fas fa-share-alt"></i> {{ __('forum_arckene::users.User.unfollow') }}')
                                    }
                                    setTimeout(function () {
                                        element.removeClass('disabled-link');
                                    }, 2500);
                                }
                            </script>
                                @endif
                            @endif
                            @if(forumPermission('USER_BAN'))
                                <a href="javascript:void(0)" onclick="ban('{{ route('forum.moderateAction', ['ban', $user['id']]) }}', $(this))" class="flex-btn main-background @if(userInfo($user['id'], 'banned')) banned @endif" style="padding: 0.5rem 1rem; background: #b00d0d">
                                @if(userInfo($user['id'], 'banned'))
                                        {{ __('forum_arckene::users.User.unban') }}
                                    @else
                                        {{ __('forum_arckene::users.User.ban') }}
                                    @endif
                            </a>
                                <script>
                                function ban (route, element) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('GET', route);
                                    xhr.send(null);
                                    document.location.reload(true);
                                }
                            </script>
                            @endif
                    </span>

                        <style>
                            .action {
                                position: absolute;
                                top: 0;
                                right: 0.3rem;
                            }
                        </style>



                    </div>
                </div>

                <div class="profile-head-2">
                    <div class="link-profile">
                        @foreach(__('forum_arckene::forum.User.link2') as $key => $value)
                            <a href="#{{ slugify($value) }}" @if($key == 0) class="active" @endif>{{ $value }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="profile-body">
                    <div class="profile-info">
                        @if(forum__userSetting(user($user['id'])->id, 'profile__show_birth'))
                            @if(!empty(userInfo(user($user['id'])->id, 'birth')))
                                <div class="mb-1 mt-3 px-2 pb-1" style="text-transform: uppercase; border-bottom: 2px solid rgba(0,0,0,0.4)">
                                    {{ __('forum_arckene::users.User.age') }} @if(forum__userSetting(user($user['id'])->id, 'profile__show_birth_year'))({{ Carbon::parse(userInfo(user($user['id'])->id, 'birth'))->age }})@endif
                                </div>
                                <div style="overflow-wrap: anywhere;">
                                    {{ Carbon::parse(userInfo(user($user['id'])->id, 'birth'))->isoFormat((forum__userSetting(user($user['id'])->id, 'profile__show_birth_year')) ? 'D MMMM Y' : 'D MMMM') }}
                                </div>
                            @endif
                        @endif

                        @if(forum__userSetting(user($user['id'])->id, 'privacy__show_location'))
                            @if(!empty(userInfo(user($user['id'])->id, 'location')))
                                <div class="mb-1 mt-3 px-2 pb-1" style="text-transform: uppercase; border-bottom: 2px solid rgba(0,0,0,0.4)">
                                    {{ __('forum_arckene::users.User.location') }}
                                </div>
                                <div style="overflow-wrap: anywhere;">
                                    {{ userInfo(user($user['id'])->id, 'location') }}
                                </div>
                            @endif
                        @endif

                        @if(!empty(userInfo(user($user['id'])->id, 'website')))
                            <div class="mb-1 mt-3 px-2 pb-1" style="text-transform: uppercase; border-bottom: 2px solid rgba(0,0,0,0.4)">{{ __('forum_arckene::users.User.website') }}</div>
                            <div style="overflow-wrap: anywhere;"><a href="//{{ userInfo(user($user['id'])->id, 'website') }}" target="_blank">{{ userInfo(user($user['id'])->id, 'website') }}</a></div>
                        @endif
                        @if(!empty(userInfo(user($user['id'])->id, 'about')))
                            <div class="mb-1 mt-3 px-2 pb-1" style="text-transform: uppercase; border-bottom: 2px solid rgba(0,0,0,0.4)">{{ __('forum_arckene::users.User.about') }}</div>
                            <div style="overflow-wrap: anywhere;">{{ userInfo(user($user['id'])->id, 'about') }}</div>
                        @endif
                    </div>
                    <div class="profile-content">
                        <div id="{{ slugify(__('forum_arckene::forum.User.link2.0')) }}" class="profile-comp">
                            @forelse(forum__getFollow()->where('id', user($user['id'])->id)->get() as $f)
                                <a href="{{ route('forum.user', [Str::slug(user($f['friend_id'])->pseudo), $f['friend_id']]) }}" class="follow-profile">
                                    <img src="{{ userInfo($f['friend_id'], 'avatar') }}" alt="{{ user($f['friend_id'])->pseudo }}" width="150px">
                                    <div style="padding: 0.3rem; color: white; text-decoration: none">{{ user($f['friend_id'])->pseudo }}</div>
                                </a>
                            @empty
                                {{ __('forum_arckene::users.User.not_following_user', ['pseudo' => user($user['id'])->pseudo]) }}
                            @endforelse
                        </div>
                        <div id="{{ slugify(__('forum_arckene::forum.User.link2.1')) }}" style="text-align: center"
                             class="profile-comp">
                            @forelse(forum__getAccess()->where('user_id', user($user['id'])->id)->take(20)->get() as $a)
                                <div class="activityRecord">
                                    {!! forum__usesTranslator($a) !!}
                                </div>
                            @empty
                                <div class="messageRecord">{{ __('forum_arckene::users.User.empty_activity') }}</div>
                            @endforelse
                        </div>
                        <div id="{{ slugify(__('forum_arckene::forum.User.link2.2')) }}" style="text-align: center"
                             class="profile-comp">
                            @forelse(forum__getThreads()->where('author_id', user($user['id'])->id)->take(20)->get() as $t)
                                <div class="messageRecord">
                                    @if(!is_null($t['forum_id']))
                                        {!! __('forum_arckene::users.User.create_thread', ['name' => '<a href="'.route('forum.thread', [Str::slug($t['name']), $t['id']]) .'">'.$t['name'].'</a>', 'date' => dateFormat($t['created_at'])]) !!}
                                    @else
                                        {!! __('forum_arckene::users.User.reply_thread', ['name' => '<a href="'.route('forum.thread', [Str::slug(forum__getThreads()->find($t['thread_id'])['name']), $t['thread_id']]) .'">'.forum__getThreads()->find($t['thread_id'])['name'].'</a>', 'date' => dateFormat($t['created_at'])]) !!}
                                    @endif
                                </div>
                            @empty
                                <div class="messageRecord">{{ __('forum_arckene::users.User.empty_thread') }}</div>
                            @endforelse
                        </div>
                        <div id="{{ slugify(__('forum_arckene::forum.User.link2.3')) }}" class="profile-comp">

                            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
                            <canvas id="myChart"></canvas>
                            <script>
                                var ctx = document.getElementById('myChart').getContext('2d');
                                var chart = new Chart(ctx, {
                                    // The type of chart we want to create
                                    type: 'line',

                                    // The data for our dataset
                                    data: {
                                        labels: [
                                            @for($i = 11; $i > 0; $i--)
                                                    @php($mktime = mktime(0, 0, 0, date("m")-$i, 1, date("Y")))
                                                    @php($m = (int)date('m', $mktime))
                                                "{{ __('forum_arckene::users.Month.' . ($m-1)) }} {{ date('Y', $mktime) }}",
                                            @endfor
                                                "{{ __('forum_arckene::users.Month.' . ((int)date('m')-1)) }} {{ date('Y') }}",
                                        ],
                                        datasets: [
                                            {
                                                label: 'Nombre de messages',
                                                backgroundColor: 'transparent',
                                                borderColor: 'rgb({{rand(0,255)}}, {{rand(0,255)}}, {{rand(0,255)}})',
                                                data: [
                                                    @for($i = 11; $i > 0; $i--)
                                                    @php($mktime = mktime(0, 0, 0, date("m")-$i, 1, date("Y")))
                                                    @php($m = (int)date('m', $mktime))
                                                    {{ $threads->where('author_id', user($user['id'])->id)->where('forum_id', null)->whereMonth('created_at', $m)->count() }},
                                                    @endfor
                                                    {{ $threads->where('author_id', user($user['id'])->id)->where('forum_id', null)->whereMonth('created_at', date('m'))->count() }}
                                                ]
                                            },
                                            {
                                                label: 'Nombre de discussion créée',
                                                backgroundColor: 'transparent',
                                                borderColor: 'rgb({{rand(0,255)}}, {{rand(0,255)}}, {{rand(0,255)}})',
                                                data: [
                                                    @for($i = 11; $i > 0; $i--)
                                                    @php($mktime = mktime(0, 0, 0, date("m")-$i, 1, date("Y")))
                                                    @php($m = (int)date('m', $mktime))
                                                    {{ $threads->where('author_id', user($user['id'])->id)->where('thread_id', null)->whereMonth('created_at', $m)->count() }},
                                                    @endfor
                                                    {{ $threads->where('author_id', user($user['id'])->id)->where('thread_id', null)->whereMonth('created_at', date('m'))->count() }}
                                                ]
                                            },
                                            {
                                                label: 'Nombre de message aimé',
                                                backgroundColor: 'transparent',
                                                borderColor: 'rgb({{rand(0,255)}}, {{rand(0,255)}}, {{rand(0,255)}})',
                                                data: [
                                                    @for($i = 11; $i > 0; $i--)
                                                    @php($mktime = mktime(0, 0, 0, date("m")-$i, 1, date("Y")))
                                                    @php($m = (int)date('m', $mktime))
                                                    {{ $likes->where('user_id', user($user['id'])->id)->whereMonth('created_at', $m)->count() }},
                                                    @endfor
                                                    {{ $likes->where('user_id', user($user['id'])->id)->whereMonth('created_at', date('m'))->count() }}
                                                ]
                                            },
                                            {
                                                label: 'Nombre de joueur suivi',
                                                backgroundColor: 'transparent',
                                                borderColor: 'rgb({{rand(0,255)}}, {{rand(0,255)}}, {{rand(0,255)}})',
                                                data: [
                                                    @for($i = 11; $i > 0; $i--)
                                                    @php($mktime = mktime(0, 0, 0, date("m")-$i, 1, date("Y")))
                                                    @php($m = (int)date('m', $mktime))
                                                    {{ $friends->where('id', user($user['id'])->id)->whereMonth('created_at', $m)->count() }},
                                                    @endfor
                                                    {{ $friends->where('id', user($user['id'])->id)->whereMonth('created_at', date('m'))->count() }}
                                                ]
                                            }
                                        ]
                                    },

                                    // Configuration options go here
                                    options: {}
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    $(document).ready(function () {
        $('.profile-body .profile-comp:first-child').fadeIn(200).toggleClass('active');
        $('.link-profile a').click(function (e) {
            e.preventDefault();
            let elem = $(this).attr('href');
            $('.link-profile a').removeClass('active');
            $(this).addClass('active');
            $('.profile-body .active').fadeOut(200);
            $('.profile-body .active').removeClass('active');
            setTimeout(function () {
                $(elem).fadeIn(200);
                $(elem).addClass('active');
            }, 200);
        });
    });

</script>