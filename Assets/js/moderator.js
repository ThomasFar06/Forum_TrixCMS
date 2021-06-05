$(document).ready(function () {
    let numberLi = $(".reportList li").length;
    let element = 3;
    showLess();

    $('#loadMore').click(function () {
        showMore();
    });

    $('#showLess').click(function () {
        showLess();
    });

    function showMore() {
        element = (element + 5 <= numberLi) ? element + 5 : numberLi;
        $('.reportList li:lt(' + element + ')').show();
    }

    function showLess() {
        element = (element - 5 < 0) ? 3 : element - 5;
        $('.reportList li').not(':lt(' + element + ')').hide();
    }

});

moderatorModal = $("#moderatorModal");
$(".moderator-tools").click(function () {
    moderatorModal.fadeIn('100');
});

$(".close").click(function () {
    moderatorModal.fadeOut('100');
});

$(document).click(function(event) {
    if($(event.target).attr('id') === moderatorModal.attr('id')) {
        moderatorModal.fadeOut('100');
    }
});

function xmlModerate(route, type, btn) {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', route);
    xhr.send(null);

    btn.parent().toggleClass(type);
    if(type === "delete") {
        setTimeout(function () {
            window.location.reload();
        }, 500);
    }
    if(type === "archiveReport") {
        btn.parent().parent().slideUp(250);
        setTimeout(function () {
            btn.parent().parent().remove();
        }, 300);
        $('#reportsNumber').text(parseInt($('#reportsNumber').text())-1);
    }
}

$('#select_forum').click(function () {
    let content = $('.flex-drop');
    content.toggleClass('open');
});

$('.flex-drop li').click(function () {
    if(!$(this).hasClass('unselectable')) {
        $('#select_forum').val($(this).text());
        $('#select_forum_data').val($(this).data('id'));
        let content = $('.flex-drop');
        content.toggleClass('open');
    }
})