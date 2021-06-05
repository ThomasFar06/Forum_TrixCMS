$("#widget__button").click(function () {
    $("#button-appear").slideToggle(200)
});

$("#widget__discord").click(function () {
    $("#discord-appear").slideToggle(200)
});

$("#widget__share").click(function () {
    $("#share-appear").slideToggle(200)
});

function buttonLoad(element) {
    if(element.is(':disabled')) {
        element.prop('disabled', false);
        element.html(element.data('value'));
    } else {
        element.attr('data-value', element.html());
        element.prop('disabled', true);
        element.html("<span class=\"icon text-white-50\"><i class=\"fas fa-spinner fa-spin\"></i></span> <span class=\"text\">"+loading_word+"</span>");
    }
}

function ajaxSend(form, button, route, reload = false) {
    form.submit(function (e) {
        e.preventDefault();
        buttonLoad(button);
        let formData = new FormData(this);
        $.ajax({
            url: route,
            type: "post",
            headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function (json) {
                buttonLoad(button);
                let j = JSON.parse(json);
                Toast().fire({
                    icon: j.alert,
                    title: j.message
                });
                if(reload) {
                    setTimeout(function () {
                        window.location.reload();
                    }, 500);
                }
            },
            error: function () {
                Toast().fire({
                    icon: "error",
                    title: "Erreur interne, veuillez actualiser la page."
                });
                buttonLoad(button);
            }
        });
    });
}
function xhrDelete(button, route, id) {
    buttonLoad($(button));
    Swal.fire({
        title: delete_sentence + " #" + id + " ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: yes,
        cancelButtonText: no
    }).then((result) => {
        if (result.value) {
            let xhr = new XMLHttpRequest();
            xhr.open('get', route);
            xhr.send();

            Toast().fire({
                icon: "success",
                title: updated
            });

            xhr.onload = function () {
                console.log(xhr.response);
            };
            setTimeout(function () {
                window.location.reload()
            }, 500)
        } else {
            buttonLoad($(button));
        }
    });
}