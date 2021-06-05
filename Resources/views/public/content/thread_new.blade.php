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
            <a href="" class="active">{{ __('forum_arckene::thread.New.breadcrumb') }}</a>
        </div>
        <div class="forum-thread">
            <div class="flex-profile">
                <div class="forum-profile">
                    <img src="{{ userInfo(user()->id, 'avatar') }}" alt="{{user()->pseudo}}">
                </div>
            </div>
            <div class="flex-content flex-post">
                <form class="sendAjaxRequest reloadAfter useParameter" ajax-action="{{ route('forum.post.threadNew') }}" data-destination="{{ route('forum.thread', ["newThread", '']) }}">
                    <div class="ajaxResponse"></div>
                    <input type="hidden" name="id" id="id" value="{{ $forum['id'] }}">
                    <label for="name">{{ __('forum_arckene::thread.New.label_name') }}</label>
                    <input type="text" name="name" id="name" placeholder="{{ __('forum_arckene::thread.New.label_name') }}" required minlength="15" maxlength="50">

                    @if(count(forum__getTags($forum['id'])) > 0)
                        <label for="tags">Tags de la discussion</label>
                        <select name="tags[]" id="tags" multiple style="width: 100%; margin-bottom: 1rem">
                            @foreach(forum__getTags($forum['id']) as $value)
                                <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                            @endforeach
                        </select>
                    @endif

                    <label for="editor">{{ __('forum_arckene::thread.New.label_editor') }}</label>
                    <div id="preview" style="display: none"></div>
                    <div style="display: flow-root">
                        <div id="editor" style="min-height: 10rem;"></div>
                    </div>
                    <span class="char-counter-editor"><span id="number">0</span> {!! __('forum_arckene::validator.char') !!}<span id="plural"></span></span>
                    <span class="float-right">
                        <button type="submit" class="flex-btn main-background"><i class="fas fa-plus"></i> {{ __('forum_arckene::thread.New.add') }}</button>
                        <button onclick="preview()" type="button" class="flex-btn main-background"><i class="fas fa-eye"></i> {{ __('forum_arckene::thread.preview') }}</button>
                    </span>
                </form>
            </div>
        </div>
        @PluginElement('public/elements/trumbowyg')
    </div>
</div>
@PluginElement('public/elements/global/footer')