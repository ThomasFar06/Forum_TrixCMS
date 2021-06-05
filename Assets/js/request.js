function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
            tmp = item.split("=");
            if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

function ajaxSend(form, button, route, reload = false, page = false) {
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
                let j = JSON.parse(json);
                Toast().fire({
                    icon: j.alert,
                    title: j.message
                });
                if(reload) {
                    setTimeout(function () {
                        if(page) {
                            if((parseInt(findGetParameter('page')) - j.page) !== 0) {
                                window.location.href = window.location.href.split('?')[0] + '?page=' + j.page + '#reply';
                            }
                        }
                        window.location.reload();

                    }, 500);
                } else {
                    buttonLoad(button);
                }
            },
            error: function () {
                let j = JSON.parse(json);
                Toast().fire({
                    icon: "error",
                    title: "Erreur interne, veuillez actualiser la page."
                });
                buttonLoad(button);
            }
        });
    });
}