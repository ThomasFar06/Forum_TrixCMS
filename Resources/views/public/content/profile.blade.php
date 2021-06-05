@PluginElement('public/elements/head')
@php
    use Carbon\Carbon;
@endphp
<div class="flex-wrapper">
    <div class="flex-container">
        <div class="flex-breadcrumb">
            <a href="{{ action('HomeController@home') }}" class=""><i class="fas fa-home"></i></a>
            <a href="{{ route('forum') }}" class="">Forum</a>
            <a href="{{ route('forum') }}" class="active">{{ user()->pseudo }}</a>
        </div>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

        <div class="forum-profile">

            <div class="profile-head">

                <div class="img-profile">
                    <img src="{{ userInfo(user()->id, 'avatar') }}" alt="{{ user()->pseudo }}">
                </div>

                <div class="info-profile">
                    <div>
                        <h1>{{ user()->pseudo }}</h1>
                    </div>

                        <div>
                            {{ userInfo(user()->id, 'title') }} @if(userInfo(user()->id, 'staff')) - Membre de l'équipe @endif
                        </div>

                    <div>
                        @php($userRanks = userRanks(user()->id))
                        @foreach($userRanks as $rank)
                            <div class="flex-rank"
                                 style="background: {{ $rank['background'] }}; color: {{ $rank['color'] }};width: fit-content;padding: 0 1.5rem; display: inline-block">
                                {{ rankName($rank['name']) }}
                            </div>
                        @endforeach
                    </div>
                    <div>
                        {{ __('forum_arckene::forum.User.register', ['date' => dateFormat(user()->created_at)]) }}.
                    </div>
                    <div>
                        Dernière activité: {!! forum__usesTranslator(forum__getAccess()->where('user_id', user()->id)->first()) !!}
                    </div>
                </div>
            </div>

            <div class="profile-head-2">
                <div class="link-profile">
                    @foreach(__('forum_arckene::forum.User.link') as $key => $value)
                        <a href="#{{ slugify($value) }}" @if($key == 0) class="active" @endif>{{ $value }}</a>
                    @endforeach

                </div>
            </div>

            <div class="profile-body">
                <div class="profile-info">
                    @if(forum__userSetting(user()->id, 'profile__show_birth'))
                        @if(!empty(userInfo(user()->id, 'birth')))
                            <div class="mb-1 mt-3 px-2 pb-1" style="text-transform: uppercase; border-bottom: 2px solid rgba(0,0,0,0.4)">
                                Age @if(forum__userSetting(user()->id, 'profile__show_birth_year'))({{ Carbon::parse(userInfo(user()->id, 'birth'))->age }})@endif
                            </div>
                            <div style="overflow-wrap: anywhere;">
                                {{ Carbon::parse(userInfo(user()->id, 'birth'))->isoFormat((forum__userSetting(user()->id, 'profile__show_birth_year')) ? 'D MMMM Y' : 'D MMMM') }}
                            </div>
                        @endif
                    @endif

                    @if(forum__userSetting(user()->id, 'privacy__show_location'))
                        @if(!empty(userInfo(user()->id, 'location')))
                            <div class="mb-1 mt-3 px-2 pb-1" style="text-transform: uppercase; border-bottom: 2px solid rgba(0,0,0,0.4)">
                                Localisation
                            </div>
                            <div style="overflow-wrap: anywhere;">
                                {{ userInfo(user()->id, 'location') }}
                            </div>
                        @endif
                    @endif

                    @if(!empty(userInfo(user()->id, 'website')))
                        <div class="mb-1 mt-3 px-2 pb-1" style="text-transform: uppercase; border-bottom: 2px solid rgba(0,0,0,0.4)">Site internet</div>
                        <div style="overflow-wrap: anywhere;"><a href="//{{ userInfo(user()->id, 'website') }}" target="_blank">{{ userInfo(user()->id, 'website') }}</a></div>
                    @endif
                    @if(!empty(userInfo(user()->id, 'about')))
                        <div class="mb-1 mt-3 px-2 pb-1" style="text-transform: uppercase; border-bottom: 2px solid rgba(0,0,0,0.4)">A propos</div>
                        <div style="overflow-wrap: anywhere;">{{ userInfo(user()->id, 'about') }}</div>
                    @endif
                </div>
                <div class="profile-content">
                    <div id="{{ slugify(__('forum_arckene::forum.User.link.0')) }}" class="profile-comp">
                        @forelse(forum__getFollow()->where('id', user()->id)->get() as $f)
                            <a href="{{ route('forum.user', [Str::slug(user($f['friend_id'])->pseudo), $f['friend_id']]) }}" class="follow-profile">
                                <img src="{{ userInfo($f['friend_id'], 'avatar') }}" alt="{{ user($f['friend_id'])->pseudo }}" width="150px">
                                <div style="padding: 0.3rem; color: white; text-decoration: none">{{ user($f['friend_id'])->pseudo }}</div>
                            </a>
                        @empty
                            {{ __('forum_arckene::users.User.not_following_user', ['pseudo' => user()->pseudo]) }}
                        @endforelse
                    </div>
                    <div id="{{ slugify(__('forum_arckene::forum.User.link.1')) }}" style="text-align: center"
                         class="profile-comp">
                        @foreach(forum__getAccess()->where('user_id', user()->id)->take(20)->get() as $a)
                            <div class="activityRecord">
                                {!! forum__usesTranslator($a) !!}
                            </div>
                        @endforeach
                    </div>
                    <div id="{{ slugify(__('forum_arckene::forum.User.link.2')) }}" style="text-align: center"
                         class="profile-comp">
                        @foreach(forum__getThreads()->where('author_id', user()->id)->take(20)->get() as $t)
                            <div class="messageRecord">
                                @if(!is_null($t['forum_id']))
                                    Création de la discussion "<a
                                            href="{{ route('forum.thread', [slugify($t['name']), $t['id']]) }}">{{ $t['name'] }}</a>
                                    ",  {{ dateFormat($t['created_at']) }}.
                                @else
                                    Réponse de la discussion "<a
                                            href="{{ route('forum.thread', [slugify(forum__getThreads()->find($t['thread_id'])['name']), $t['thread_id']]) }}">{{ forum__getThreads()->find($t['thread_id'])['name'] }}</a>
                                    ",  {{ dateFormat($t['created_at']) }}.
                                @endif

                            </div>
                        @endforeach
                    </div>
                    <div id="{{ slugify(__('forum_arckene::forum.User.link.3')) }}" class="profile-comp">

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
                                                {{ $threads->where('author_id', user()->id)->where('forum_id', null)->whereMonth('created_at', $m)->count() }},
                                                @endfor
                                                {{ $threads->where('author_id', user()->id)->where('forum_id', null)->whereMonth('created_at', date('m'))->count() }}
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
                                                {{ $threads->where('author_id', user()->id)->where('thread_id', null)->whereMonth('created_at', $m)->count() }},
                                                @endfor
                                                {{ $threads->where('author_id', user()->id)->where('thread_id', null)->whereMonth('created_at', date('m'))->count() }}
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
                                                {{ $likes->where('user_id', user()->id)->whereMonth('created_at', $m)->count() }},
                                                @endfor
                                                {{ $likes->where('user_id', user()->id)->whereMonth('created_at', date('m'))->count() }}
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
                                                {{ $friends->where('id', user()->id)->whereMonth('created_at', $m)->count() }},
                                                @endfor
                                                {{ $friends->where('id', user()->id)->whereMonth('created_at', date('m'))->count() }}
                                            ]
                                        }
                                    ]
                                },

                                // Configuration options go here
                                options: {}
                            });
                        </script>

                    </div>
                    <div id="{{ slugify(__('forum_arckene::forum.User.link.4')) }}" class="profile-comp">
                        <div class="flex-profile-post">
                            <form class="sendAjaxRequest" id="setting_form"
                                  ajax-action="{{ route('forum.post.setting') }}">
                                <div class="ajaxResponse mb-3"></div>

                                <div class="m-flex">
                                    <div class="flex-size-1">
                                        <div class="flex-input-group-flex">
                                            <label for="imgs">Avatar</label>

                                            <input onkeyup="$('#img').prop('value', $(this).val())" id="imgs"
                                                   value="{{ userInfo(user()->id, 'avatar') }}">
                                            <button id="upload_img" type="button"><i class="fas fa-upload"></i></button>

                                            <input type="file" id="img_upload" style="display: none"
                                                   accept="image/png, image/jpeg, image/jpg, image/gif">
                                            <input id="img" type="hidden" name="avatar"
                                                   value="{{ userInfo(user()->id, 'avatar') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="m-flex">
                                    <div class="flex-size-4">
                                        <div class="flex-input-flex">
                                            <label for="title">{{ __('forum_arckene::users.Setting.title') }}</label>
                                            <input type="text" id="title" name="title"
                                                   value="{{ userInfo(user()->id, 'title') }}">
                                        </div>
                                    </div>

                                    <div class="flex-size-3">
                                        <div class="flex-input-flex">
                                            <label for="birth">{{ __('forum_arckene::users.Setting.birthdate') }}</label>
                                            <input type="date" id="birth" name="birth"
                                                   value="{{ userInfo(user()->id, 'birth') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="m-flex">
                                    <div class="flex-size-1">
                                        <div class="flex-input-flex">
                                            <label for="website">{{ __('forum_arckene::users.Setting.website') }}</label>
                                            <input type="text" id="website" name="website"
                                                   value="{{ userInfo(user()->id, 'website') }}">
                                        </div>
                                    </div>
                                    <div class="flex-size-1">
                                        <div class="flex-input-flex">
                                            <label for="location">{{ __('forum_arckene::users.Setting.location') }}</label>
                                            <input type="text" id="location" name="location"
                                                   value="{{ userInfo(user()->id, 'location') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="m-flex">
                                    <div class="flex-size-1">
                                        <div class="flex-input-flex">
                                            <label for="about">{{ __('forum_arckene::users.Setting.about') }}</label>
                                            <textarea name="about" id="about"
                                                      rows="3">{{ userInfo(user()->id, 'about') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="m-flex">
                                    <div class="flex-size-1">
                                        <div class="flex-input-flex">
                                            <label for="editor">{{ __('forum_arckene::users.Setting.signature') }}</label>
                                            <div id="preview"
                                                 style="display: none">{!! userInfo(user()->id, 'signature') !!}</div>
                                            <div style="display: flow-root">
                                                <div id="editor"
                                                     style="min-height: 10rem;">{!! userInfo(user()->id, 'signature') !!}</div>
                                            </div>
                                            <button onclick="preview()" type="button" class="flex-btn main-background"><i
                                                        class="fas fa-eye"></i> {{ __('forum_arckene::thread.preview') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="profile__show_birth" id="profile__show_birth" @if(forum__userSetting(user()->id, 'profile__show_birth')) checked @endif>
                                    <label for="profile__show_birth" class="custom-control-label">{{ __('forum_arckene::users.Setting.profile__show_birth') }}</label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="profile__show_birth_year" id="profile__show_birth_year" @if(forum__userSetting(user()->id, 'profile__show_birth_year')) checked @endif>
                                    <label for="profile__show_birth_year" class="custom-control-label">{{ __('forum_arckene::users.Setting.profile__show_birth_year') }}</label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="email_receive_onpost" id="email_receive_onpost" @if(forum__userSetting(user()->id, 'email_receive_onpost')) checked @endif>
                                    <label for="email_receive_onpost" class="custom-control-label">{{ __('forum_arckene::users.Setting.email_receive_onpost') }}</label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="email_receive_onreply" id="email_receive_onreply" @if(forum__userSetting(user()->id, 'email_receive_onreply')) checked @endif>
                                    <label for="email_receive_onreply" class="custom-control-label">{{ __('forum_arckene::users.Setting.email_receive_onreply') }}</label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="content__show_signature" id="content__show_signature" @if(forum__userSetting(user()->id, 'content__show_signature')) checked @endif>
                                    <label for="content__show_signature" class="custom-control-label">{{ __('forum_arckene::users.Setting.content__show_signature') }}</label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="privacy__show_location" id="privacy__show_location" @if(forum__userSetting(user()->id, 'privacy__show_location')) checked @endif>
                                    <label for="privacy__show_location" class="custom-control-label">{{ __('forum_arckene::users.Setting.privacy__show_location') }}</label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="privacy__show_online" id="privacy__show_online" @if(forum__userSetting(user()->id, 'privacy__show_online')) checked @endif>
                                    <label for="privacy__show_online" class="custom-control-label">{{ __('forum_arckene::users.Setting.privacy__show_online') }}</label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="privacy__show_activity" id="privacy__show_activity" @if(forum__userSetting(user()->id, 'privacy__show_activity')) checked @endif>
                                    <label for="privacy__show_activity" class="custom-control-label">{{ __('forum_arckene::users.Setting.privacy__show_activity') }}</label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="rank__show" id="rank__show" @if(forum__userSetting(user()->id, 'rank__show')) checked @endif>
                                    <label for="rank__show" class="custom-control-label">{{ __('forum_arckene::users.Setting.rank__show') }}</label>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div id="{{ slugify(__('forum_arckene::forum.User.link.5')) }}" class="profile-comp">

                        <form class="sendAjaxRequest" id="notification_forum"
                              ajax-action="{{ route('forum.post.notification') }}">
                            <div class="ajaxResponse mb-3"></div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="post__watched_forum"
                                       id="post__watched_forum"
                                       @if(forum__userSetting(user()->id, 'post__watched_forum')) checked @endif>
                                <label for="post__watched_forum"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.post__watched_forum') }}</label>
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="post__watched_thread"
                                       id="post__watched_thread"
                                       @if(forum__userSetting(user()->id, 'post__watched_thread')) checked @endif>
                                <label for="post__watched_thread"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.post__watched_thread') }}</label>
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="post_mention"
                                       id="post_mention"
                                       @if(forum__userSetting(user()->id, 'post_mention')) checked @endif>
                                <label for="post_mention"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.post_mention') }}</label>
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="message_quoting"
                                       id="message_quoting"
                                       @if(forum__userSetting(user()->id, 'message_quoting')) checked @endif>
                                <label for="message_quoting"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.message_quoting') }}</label>
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="message_react"
                                       id="message_react"
                                       @if(forum__userSetting(user()->id, 'message_react')) checked @endif>
                                <label for="message_react"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.message_react') }}</label>
                            </div>

                            <div class="custom-control custom-switch d-none">
                                <input type="checkbox" class="custom-control-input" name="chat__private"
                                       id="chat__private"
                                       @if(forum__userSetting(user()->id, 'chat__private')) checked @endif>
                                <label for="chat__private"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.chat__private') }}</label>
                            </div>

                            <div class="custom-control custom-switch d-none">
                                <input type="checkbox" class="custom-control-input" name="chat__poke" id="chat__poke"
                                       @if(forum__userSetting(user()->id, 'chat__poke')) checked @endif>
                                <label for="chat__poke"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.chat__poke') }}</label>
                            </div>

                            <div class="custom-control custom-switch d-none">
                                <input type="checkbox" class="custom-control-input" name="chat__message"
                                       id="chat__message"
                                       @if(forum__userSetting(user()->id, 'chat__message')) checked @endif>
                                <label for="chat__message"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.chat__message') }}</label>
                            </div>

                            <div class="custom-control custom-switch d-none">
                                <input type="checkbox" class="custom-control-input" name="profile__react"
                                       id="profile__react"
                                       @if(forum__userSetting(user()->id, 'profile__react')) checked @endif>
                                <label for="profile__react"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.profile__react') }}</label>
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="profile__comment"
                                       id="profile__comment"
                                       @if(forum__userSetting(user()->id, 'profile__comment')) checked @endif>
                                <label for="profile__comment"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.profile__comment') }}</label>
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="profile__comment_reply"
                                       id="profile__comment_reply"
                                       @if(forum__userSetting(user()->id, 'profile__comment_reply')) checked @endif>
                                <label for="profile__comment_reply"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.profile__comment_reply') }}</label>
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="profile__friend_request"
                                       id="profile__friend_request"
                                       @if(forum__userSetting(user()->id, 'profile__friend_request')) checked @endif>
                                <label for="profile__friend_request"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.profile__friend_request') }}</label>
                            </div>

                            <div class="custom-control custom-switch d-none">
                                <input type="checkbox" class="custom-control-input" name="profile__trophy"
                                       id="profile__trophy"
                                       @if(forum__userSetting(user()->id, 'profile__trophy')) checked @endif>
                                <label for="profile__trophy"
                                       class="custom-control-label">{{ __('forum_arckene::users.Notification.profile__trophy') }}</label>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $('#notification_forum input').change(function () {
        $('#notification_forum').submit();
    });

    $('#setting_form input').change(function () {
        $('#setting_form').submit();
    });
    $('#setting_form textarea').on("change", function () {
        $('#setting_form').submit();
    });
    $('#setting_form #editor').on("tbwblur", function () {
        $('#setting_form').submit();
    });


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

    let img = $('#img_upload');
    let value_default_ui = $('#upload_img').html();

    $('#upload_img').click(function () {
        img.click();
    });

    img.change(function () {
        let formData = new FormData();
        formData.append('img', img.prop('files')[0]);

        $.ajax({
            url: "{{ route('forum.post.avatar') }}",
            type: "post",
            headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
            },
            dataType: "json",
            contentType: false,
            processData: false,
            data: formData,
            success: function (json) {
                if (json.response === 2) {
                    return Toast().fire({
                        icon: "warning",
                        title: "{{ __('messages.DASHBOARD__ADMIN.Pages.General.Import.FilesEmpty') }}"
                    });
                }

                $('#imgs').prop('value', 'Chargement...');
                $('#img').prop('value', json.data);

                $('#upload_img').prop('disabled', true);
                $('#upload_img').removeClass('btn-primary').addClass('btn-success');
                $('#upload_img').html("<i class='fas fa-check'></i>");

                setTimeout(function (){
                    document.location.reload()
                }, 200)
            },
            error: function () {
                $('#upload_img').html(value_default_ui);

                Toast().fire({
                    icon: "error",
                    title: "{{ __('messages.Error.Internal') }}"
                });
            }
        });
    });
</script>

@PluginElement('public/elements/trumbowyg')
@PluginElement('public/elements/global/footer')