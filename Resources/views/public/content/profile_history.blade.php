@PluginElement('public/elements/head')
@php
    use Carbon\Carbon;
@endphp
<div class="flex-wrapper">
    <div class="flex-container">
        <div class="flex-breadcrumb">
            <a href="{{ action('HomeController@home') }}" class=""><i class="fas fa-home"></i></a>
            <a href="{{ route('forum') }}" class="">Forum</a>
            <a href="{{ route('forum.profile') }}">{{ user()->pseudo }}</a>
            <a href="javascript:void(0)" class="active">Historique</a>
        </div>

        <div class="forum-profile text-center" style="padding: 1rem; margin: 0.3rem">
            {!! $histories->links('421339390::public.elements.pagination') !!}

            @forelse($histories as $h)
                <div style="background: rgba(0,0,0,0.2); padding: 0.5rem; border-radius: 0.3rem; margin: 0.3rem 0;">
                    {!! forum__usesTranslator($h) !!}
                </div>
            @empty
                Aucune donnée
            @endforelse

            {!! $histories->links('421339390::public.elements.pagination') !!}

        </div>

    </div>
</div>
@PluginElement('public/elements/global/footer')