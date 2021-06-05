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
            <a href="#" >{{ __('forum_arckene::thread.Edit.breadcrumb') }}</a>
            <a href="" class="active">{!! $parent['name'] !!}</a>
        </div>
        <div class="forum-thread">
            <div class="flex-profile">
                <div class="forum-profile">
                    <img src="{{ userInfo(user()->id, 'avatar') }}" alt="{{user(user()->id)->pseudo}}">
                </div>
            </div>
            <div class="flex-content flex-post">
                <form class="sendAjaxRequest reloadAfter" ajax-action="{{ route('forum.post.threadEdit') }}" data-destination="{{ route('forum.thread', [slugify($parent['name']), (isset($thread['thread_id']) ? $thread['thread_id'] : $thread['id'])]) }}">
                    <div class="ajaxResponse"></div>
                    <div class="flex-info">
                        <h1 style="text-align: center">
                            @if(is_null($thread['thread_id']))
                                {{ __('forum_arckene::thread.Edit.title_t', ['id' => $thread['id']]) }}
                            @else
                                {{ __('forum_arckene::thread.Edit.title', ['id' => $thread['id']]) }}
                            @endif
                        </h1>
                    </div>
                    <input type="hidden" name="id" id="id" value="{{ $thread['id'] }}">
                    @if(is_null($thread['thread_id']))
                        <label for="name">{{ __('forum_arckene::thread.Edit.label_name') }}</label>
                        <input type="text" value="{{ $thread['name'] }}" name="name" id="name" placeholder="{{ __('forum_arckene::thread.Edit.label_name') }}">

                        @if(count(forum__getTags($forum['id'])) > 0)
                            <label for="tags">Tags de la discussion</label>
                            <select name="tags[]" id="tags" multiple style="width: 100%; margin-bottom: 1rem">
                                @foreach(forum__getTags($forum['id']) as $value)
                                    <option value="{{ $value['id'] }}" @if(in_array($value['id'], json_decode($thread['tags']))) selected @endif>{{ $value['name'] }}</option>
                                @endforeach
                            </select>
                        @endif
                    @else
                        <input type="hidden" value="{{ $parent['name'] }}" name="name">
                    @endif
                    <label for="editor">{{ __('forum_arckene::thread.Edit.label_editor') }}</label>
                    <div id="preview" style="display: none"></div>
                    <div style="display: flow-root">
                        <div id="editor" style="min-height: 10rem;">{!! $thread['message'] !!}</div>
                    </div>
                    <span class="char-counter-editor"><span id="number">{{ strlen($thread['message']) }}</span> {!! __('forum_arckene::validator.char') !!}<span id="plural">s</span></span>
                    <span class="float-right">
                        <button type="submit" class="flex-btn main-background"><i class="fas fa-edit"></i> {{ __('forum_arckene::thread.Edit.edit') }}</button>
                        <button onclick="preview()" type="button" class="flex-btn main-background"><i class="fas fa-eye"></i> {{ __('forum_arckene::thread.preview') }}</button>
                    </span>
                </form>
            </div>
        </div>
        @PluginElement('public/elements/trumbowyg')
    </div>
</div>
@PluginElement('public/elements/global/footer')