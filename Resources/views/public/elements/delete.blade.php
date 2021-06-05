@if(forumPermission('THREAD_DELETE_OWN') || forumPermission('THREAD_DELETE_OTHER'))
    <div class="flex-info">
        <div id="deleteModal" class="flex-modal">
            <div class="modal-content">
                <div class="flex-modal-header">
                    <h1>{{ __('forum_arckene::thread.Delete.h1') }}</h1>
                    <h2>{{ __('forum_arckene::thread.Delete.h2') }}<span id="th_id2"></span></h2>
                    <a class="close"><i class="fas fa-times"></i></a>
                </div>
                <div class="flex-modal-body" style="text-align: center">

                    {{ __('forum_arckene::thread.Delete.message') }}
                    <p>{{ __('forum_arckene::thread.Delete.cond_1') }}</p>
                    <p>{{ __('forum_arckene::thread.Delete.cond_2') }}</p>

                    <div class="lg-flex">
                        <div class="flex-size-1" style="margin: 0.3rem">
                            <a href="javascript:void(0)" id="delete" class="flex-btn main-background w-100 disabled-link">{{ __('forum_arckene::thread.Delete.yes') }}</a>
                        </div>
                        <div class="flex-size-1" style="margin: 0.3rem">
                            <a href="javascript:void(0)" class="canClose flex-btn main-background w-100">{{ __('forum_arckene::thread.Delete.no') }}</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        deleteModal = $("#deleteModal");
        $(".delete-button").click(function () {
            setTimeout(function () {
                $('#delete').removeClass('disabled-link');
            }, 5000);
            deleteModal.fadeIn('100');
            $('#th_id2').html(($(this).data('thid')));
            $('#delete').data('id', $(this).data('id'))
        });

        $('.canClose').click(function () {
            deleteModal.fadeOut('100');
            setTimeout(function () {
                $('#delete').addClass('disabled-link');
            }, 150);
        });

        $(".close").click(function () {
            deleteModal.fadeOut('100');
            setTimeout(function () {
                $('#delete').addClass('disabled-link');
            }, 150);
        });

        $(document).click(function(event) {
            if($(event.target).attr('id') === deleteModal.attr('id')) {
                deleteModal.fadeOut('100');
                setTimeout(function () {
                    $('#delete').addClass('disabled-link');
                }, 150);
            }
        });

        $('#delete').click(function () {
            let xhr = new XMLHttpRequest();
            xhr.open('GET', '{{ route('forum.threadDelete', ['']) }}' + '/' + parseInt($(this).data('id')));
            xhr.send(null);
            setTimeout(function () {
                window.location.reload();
            }, 750);
        });
    </script>
@endif

<style>
    .flex-modal-body p {
        font-size: 80%;
    }
</style>