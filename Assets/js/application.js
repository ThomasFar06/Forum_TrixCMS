const controlTime = 250;

const capitalize = (s) => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
};

$('.ajaxResponse').click(function () {
    $(this).fadeOut(controlTime);
});

$('.sendAjaxRequest').submit(function (e) {
    // Cancel the event of form submitting
    e.preventDefault();

    // Get feedback div for ajax response
    let feedback = $(this).find('.ajaxResponse');

    // Get form data form sending
    let formData = new FormData(this);

    // Get route to send the resquest
    let route = $(this).attr('ajax-action');

    // Get information about reloading and redirect the page after
    let reload = $(this).hasClass('reloadAfter');
    let parameter = $(this).hasClass('useParameter');
    let destination = $(this).data('destination');

    //Submit button behaviour
    let submit = $(this).find(':submit');

    submit.attr('data-value', submit.html());
    submit.prop('disabled', true);
    submit.html("<i class=\"fas fa-spinner fa-spin\"></i> " + FORUM_LOADING_WORD);

    //Ajax request
    $.ajax({
        url: route,
        type: "post",
        headers: {
            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
        },
        data: formData,
        contentType: false,
        processData: false,
        success: (data) => {
            if(data['success']) {
                feedback.removeClass('error');
                feedback.addClass('success');
                feedback.html(capitalize(data['message']));
                feedback.fadeIn(controlTime);

                if(reload) {
                    setTimeout(function () {
                        if(destination) {
                            window.location.href = destination + (parameter ? data['parameter'] : '')
                        } else {
                            window.location.reload()
                        }
                    }, controlTime*2);
                }

            } else {

                feedback.removeClass('success');
                feedback.addClass('error');
                feedback.html(capitalize(data['message'][0]));
                feedback.fadeIn(controlTime);

                submit.prop('disabled', false);
                submit.html(submit.data('value'));
            }
        },
        error: function () {

            feedback.removeClass('success');
            feedback.addClass('error');
            feedback.html(FORUM_INTERNAL_ERROR);
            feedback.fadeIn(controlTime);

            submit.prop('disabled', false);
            submit.html(submit.data('value'));
        }
    });
});