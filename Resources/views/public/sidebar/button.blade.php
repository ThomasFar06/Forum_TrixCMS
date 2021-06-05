@if($configuration['widget__button'])
    <div class="flex-sidebar-element">
        <div class="forum-profile-button">
            <a href="{{ $configuration['button_link'] }}" class="flex-btn main-background w-100 block">{!! $configuration['button_name'] !!}</a>
        </div>
    </div>
@endif