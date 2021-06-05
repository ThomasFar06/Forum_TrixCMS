@if(forumPermission('THREAD_REPORT'))
    <div class="flex-info">
        <div id="reportModal" class="flex-modal">
            <div class="modal-content">
                <div class="flex-modal-header">
                    <h1>{{ __('forum_arckene::forum.Report.h1') }}</h1>
                    <h2>{{ __('forum_arckene::forum.Report.h2') }}<span id="th_id"></span></h2>
                    <a class="close"><i class="fas fa-times"></i></a>
                </div>
                <div class="flex-modal-body">

                    <form class="sendAjaxRequest reloadAfter" ajax-action="{{ route('forum.post.threadReport') }}">
                        <div class="ajaxResponse"></div>
                        <input type="hidden" id="thread_id_report" name="thread_id_report">
                        <label for="report_reason">{{ __('forum_arckene::forum.Report.label') }}</label>
                        <textarea class="flex-textarea" name="report_reason" id="report_reason" rows="3" placeholder="{{ __('forum_arckene::forum.Report.placeholder') }}"></textarea>
                        <button class="flex-btn w-100 main-background">{{ __('forum_arckene::forum.Report.send') }}</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        reportModal = $("#reportModal");
        $(".report-button").click(function () {
            reportModal.fadeIn('100');
            $('#th_id').html(($(this).data('thid')));
            $('#thread_id_report').val(($(this).data('id')));
        });

        $(".close").click(function () {
            reportModal.fadeOut('100');
        });

        $(document).click(function(event) {
            if($(event.target).attr('id') === reportModal.attr('id')) {
                reportModal.fadeOut('100');
            }
        });
    </script>
@endif

