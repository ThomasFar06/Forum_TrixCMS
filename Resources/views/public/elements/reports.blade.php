@if(forumPermission('REPORT_LIST') || forumPermission('REPORT_ACTION'))
    <div class="flex-info">
        <div id="reportsModal" class="flex-modal">
            <div class="modal-content">
                <div class="flex-modal-header">
                    <h1>{{ __('forum_arckene::forum.Report.h1') }}</h1>
                    <h2>{{ __('forum_arckene::forum.Report.h2') }}<span id="threa_id"></span></h2>
                    <a class="close"><i class="fas fa-times"></i></a>
                </div>
                <div class="flex-modal-body">

                    @if(forumPermission('REPORT_LIST'))
                        <hr>
                        <p style="text-align: center;">
                            {!! __('forum_arckene::forum.Moderate.report_exist', ['number' => '0']) !!}
                        </p>

                        <ul id="reportsList" class="reportList">
                        </ul>
                        @if(count($reports->where('thread_id', $thread['id'])->where('archive', null)->orWhere('archive', 0)->get()) > 3)
                            <div class="lg-flex">
                                <div class="flex-size-1" style="margin: 0.3rem">
                                    <button id="aloadMore" type="submit" class="flex-btn main-background w-100">{{ __('forum_arckene::forum.Moderate.show_more') }}</button>
                                </div>
                                <div class="flex-size-1" style="margin: 0.3rem">
                                    <button id="ashowLess" type="submit" class="flex-btn main-background w-100">{{ __('forum_arckene::forum.Moderate.show_less') }}</button>
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
                                    <div class="state w-100">
                                        <a href="#" onclick="xmlModerate('{{ route('forum.moderateAction', ['delete', '']) }}/', 'delete', $(this))">{{ __('forum_arckene::forum.Moderate.delete') }} / message</a>
                                    </div>
                                </div>
                            @endif
                            @if(forumPermission('USER_BAN'))
                                <div class="flex-size-1" style="padding: 0.3rem">
                                    <div class="state w-100">
                                        <a href="#" onclick="xmlModerate('{{ route('forum.moderateAction', ['ban', '']) }}', 'ban', $(this))">{{ __('forum_arckene::forum.Moderate.ban') }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <script>
        reportsModal = $("#reportsModal");
        let numberLi;
        let element;

        element = 3;
        reports = $("#reportsList");
        $('#aloadMore').click(function () {
            showMore();
        });

        $('#ashowLess').click(function () {
            showLess();
        });

        function showMore() {
            element = (element + 5 <= numberLi) ? element + 5 : numberLi;
            $('#reportsList li:lt(' + element + ')').show();
        }

        function showLess() {
            element = (element - 5 < 0) ? 3 : element - 5;
            $('#reportsList li').not(':lt(' + element + ')').hide();
        }


        $(".reports_tools").click(function () {
            reports.html('');
            $('#threa_id').html(($(this).data('thid')));

            $.ajax({
                url: '{{ route('forum.post.threadGetReport') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
                },
                data: {
                    id : $(this).data('thid'),    // id of selected record
                },
                success: function(response){
                    let data = JSON.parse(response);
                    $('#reportsNumber').html(data.length);
                    data.forEach((item, index) => {
                        reports.append(
                            '<li>' +
                            '<div class="number">#'+(parseInt(index)+1)+'</div>' +
                            '<div class="content">' +
                            '<div class="reason">'+item['reason']+'</div>' +
                            '<div class="info">'+item['info']+'</div>' +
                            '</div><div class="action">' +
                            '<button onclick="xmlModerate(\'http://trixcms.test/forum/xml/moderate/archiveReport/' + item['id'] +'\', \'archiveReport\', $(this))" class="flex-btn main-background"><i class="fas fa-archive"></i></button>' +
                            '</div>' +
                            '</li>');
                    });
                    numberLi = data.length;
                }
            });
            showLess();
            reportsModal.fadeIn('100');

        });




        $(".close").click(function () {
            reportsModal.fadeOut('100');
        });

        $(document).click(function(event) {
            if($(event.target).attr('id') === reportsModal.attr('id')) {
                reportsModal.fadeOut('100');
            }
        });
    </script>
@endif

