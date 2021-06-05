@if($configuration['widget__staff'])
    <div class="flex-sidebar-element staff">
        @php($count=0)
        @foreach(forum__getUsers()->get() as $u)
            @if(userInfo($u['id'], 'staff') && forum__userIsOnline($u['id']))
                @php($count++)
            @endif
        @endforeach
        <div class="sidebar-title">
            <div style="margin-bottom: 0.6rem">{!! trans_choice('forum_arckene::forum.Widget.Staff.0', $count) !!}</div>

            @foreach(forum__getUsers()->get() as $u)
                @if(userInfo($u['id'], 'staff') && forum__userIsOnline($u['id']))

                    <a href="{{ route('forum.user', [user($u['id'])->pseudo, $u['id']]) }}" data-tooltip="{{ user($u['id'])->pseudo }}" data-tooltip-location="top" style="margin: 0 0.3rem 0.6rem 0; display: inline-block;">
                        <img src="{{ userInfo($u['id'], 'avatar') }}" alt="{{ user($u['id'])->pseudo }}" width="50px" height="50px">
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endif