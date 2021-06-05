<!-- PROFILE MODAL -->
<div class="flex-sidebar-element">
    @if(tx_auth()->isLogin())
        <div style="margin-bottom: 1rem">
            <div class="forum-profile-image">
                <img src="{{ userInfo(user()->id, 'avatar') }}" alt="{{user()->pseudo}}">
            </div>
            <div class="forum-profile-info">
                <div class="user">{{ user()->pseudo }}</div>
                <p>{{ userInfo(user()->id, 'title') }}</p>
            </div>
        </div>
        <div class="forum-profile-button">
            <a href="{{ route('forum.profile') }}" class="flex-btn main-background w-100 block"><i class="fas fa-user"></i> {{ __('forum_arckene::forum.Profile.profile') }}</a>
            <a href="{{ route('forum.profile.history') }}" class="flex-btn main-background w-100 block"><i class="fas fa-list"></i> {{ __('forum_arckene::forum.Profile.history') }}</a>
        </div>
    @else
        <div class="forum-profile-button">
            <a href="{{ action('Auth\User\LoginController@userLogin') }}" class="flex-btn main-background w-100 block"><i class="fas fa-lock-open"></i> {{ __('forum_arckene::forum.Profile.login') }}</a>
            <a href="{{ action('Auth\User\RegisterController@userRegister') }}" class="flex-btn main-background w-100 block"><i class="fas fa-sign-in-alt"></i> {{ __('forum_arckene::forum.Profile.register') }}</a>
        </div>
    @endif
</div>
<!-- END PROFILE MODAL -->