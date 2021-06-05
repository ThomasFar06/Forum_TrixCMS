@if(forumPermission('MODERATOR_TOOLS'))
    <span class="moderator-tools">
    <i class="fas fa-wrench"></i>
</span>
    <div id="moderatorModal" class="flex-modal">
        <div class="modal-content">
            <div class="flex-modal-header">
                <h1>{{ __('forum_arckene::forum.Moderate.h1') }}</h1>
                <h2>{{ __('forum_arckene::forum.Moderate.h2', ['id' => $thread['id']]) }}</h2>
                <a class="close"><i class="fas fa-times"></i></a>
            </div>
            <div class="flex-modal-body">

                <div class="lg-flex">
                    @if(forumPermission('THREAD_PIN'))
                        <div class="flex-size-1" style="padding: 0.3rem">
                            <div class="state {{ $thread['pin'] ? "pin" : "" }} w-100">
                                <a href="#" onclick="xmlModerate('{{ route('forum.moderateAction', ['pin', $thread['id']]) }}', 'pin', $(this))">{{ __('forum_arckene::forum.Moderate.pin') }}</a>
                            </div>
                        </div>
                    @endif
                    @if(forumPermission('THREAD_LOCK'))
                        <div class="flex-size-1" style="padding: 0.3rem">
                            <div class="state {{ $thread['lock'] ? "lock" : "" }} w-100">
                                <a href="#" onclick="xmlModerate('{{ route('forum.moderateAction', ['lock', $thread['id']]) }}', 'lock', $(this))">{{ __('forum_arckene::forum.Moderate.lock') }}</a>
                            </div>
                        </div>
                    @endif
                    @if(forumPermission('THREAD_ARCHIVE'))
                        <div class="flex-size-1" style="padding: 0.3rem">
                            <div class="state {{ $thread['archive'] ? "archive" : "" }} w-100">
                                <a href="#" onclick="xmlModerate('{{ route('forum.moderateAction', ['archive', $thread['id']]) }}', 'archive', $(this))">{{ __('forum_arckene::forum.Moderate.archive') }}</a>
                            </div>
                        </div>
                    @endif
                </div>

                @if(forumPermission('THREAD_MOVE_ALL'))
                    <hr>
                    <?php
                    $GLOBALS['forum'] = $forums;
                    $GLOBALS['thread'] = $thread;

                    function generateDropSelect($id) {
                    $forum = $GLOBALS['forum'];
                    $thread = $GLOBALS['thread'];
                    foreach ($forum->where('forum_id', $id) as $f) { if(!$f['category']) { ?>
                    <li @if($f['forum_id'] == 0 || $f['id'] == $thread['forum_id']) class="unselectable" @endif data-id="{{ $f['id'] }}">{{ $f['name'] }}</li>
                    <?php } else { ?>
                    <li @if($f['forum_id'] == 0 || $f['id'] == $thread['forum_id']) class="unselectable" @endif data-id="{{ $f['id'] }}">{{ $f['name'] }}</li>
                    <ul>
                        @php(generateDropSelect($f['id']))
                    </ul>
                    <?php }}} ?>

                    <form id="form_form_update_thread_location">
                        <div class="flex-dropdown selector">
                            <div class="validator">
                                <input type="hidden" name="thread_id" value="{{ $thread['id'] }}">
                                <input type="hidden" id="select_forum_data" name="select_forum_data">
                                <input type="text" id="select_forum" readonly placeholder="{{ __('forum_arckene::forum.Moderate.category_choose') }}">
                            </div>
                            <ul class="flex-drop">
                                @php(generateDropSelect(0))
                            </ul>
                        </div>

                        <button id="submit_form_update_thread_location" style="margin-top: 0.5rem" type="submit" class="flex-btn main-background w-100">{{ __('forum_arckene::forum.Moderate.update', ['id' => $thread['id']]) }}</button>
                    </form>
                    <style>.flex-drop.open { max-height: calc(2.7em * {{ count($forums) }}); z-index: 1; }</style>
                @endif

                @if(forumPermission('REPORT_LIST'))
                    <hr>
                    <p style="text-align: center;">
                        @if(count($reports->where('thread_id', $thread['id'])->where('archive', null)->orWhere('archive', 0)->get()) == 0)
                            {{ __('forum_arckene::forum.Moderate.report_empty') }}
                        @else
                            {!! __('forum_arckene::forum.Moderate.report_exist', ['number' => count($reports->where('thread_id', $thread['id'])->where('archive', null)->orWhere('archive', 0)->get())]) !!}
                        @endif
                    </p>

                    <ul class="reportList">
                        @php($i=0)
                        @foreach($reports->where('thread_id', $thread['id'])->where('archive', null)->orWhere('archive', 0)->get() as $r)
                            @php($i++)
                            <li>
                                <div class="number">
                                    #{{$i}}
                                </div>
                                <div class="content">
                                    <div class="reason">{{ $r['reason'] }}</div>
                                    <div class="info">Par {{ user()->pseudo }}, {{ dateFormat($r['created_at']) }}</div>
                                </div>
                                @if(forumPermission('REPORT_ACTION'))
                                    <div class="action">
                                        <button onclick="xmlModerate('{{ route('forum.moderateAction', ['archiveReport', $r['id']]) }}', 'archiveReport', $(this))" class="flex-btn main-background">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @if(count($reports->where('thread_id', $thread['id'])->where('archive', null)->orWhere('archive', 0)->get()) > 3)
                        <div class="lg-flex">
                            <div class="flex-size-1" style="margin: 0.3rem">
                                <button id="loadMore" type="submit" class="flex-btn main-background w-100">{{ __('forum_arckene::forum.Moderate.show_more') }}</button>
                            </div>
                            <div class="flex-size-1" style="margin: 0.3rem">
                                <button id="showLess" type="submit" class="flex-btn main-background w-100">{{ __('forum_arckene::forum.Moderate.show_less') }}</button>
                            </div>
                        </div>
                    @endif
                @endif

                @if(forumPermission('REPORT_ACTION'))
                    <hr>
                    <p style="text-align: center;">
                        {{ __('forum_arckene::forum.Moderate.report_action') }}
                    </p>
                    <div class="lg-flex">
                        @if(forumPermission('THREAD_DELETE_OTHER'))
                            <div class="flex-size-1" style="padding: 0.3rem">
                                <div class="state {{ $thread['delete'] ? "delete" : "" }} w-100">
                                    <a href="#" onclick="xmlModerate('{{ route('forum.moderateAction', ['delete', $thread['id']]) }}', 'delete', $(this))">{{ __('forum_arckene::forum.Moderate.delete') }}</a>
                                </div>
                            </div>
                        @endif
                        @if(forumPermission('USER_BAN'))
                            <div class="flex-size-1" style="padding: 0.3rem">
                                <div class="state {{ userInfo($thread['author_id'], 'ban') ? "ban" : "" }} w-100">
                                    <a href="#" onclick="xmlModerate('{{ route('forum.moderateAction', ['ban', $thread['id']]) }}', 'ban', $(this))">{{ __('forum_arckene::forum.Moderate.ban') }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
    <script src="@PluginAssets('js/moderator.js')"></script>
@endif