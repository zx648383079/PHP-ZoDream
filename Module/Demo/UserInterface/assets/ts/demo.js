$(function () {
    var refresh = function () {
        var w = $(window).width();
        if (w > 650) {
            $('.metro-grid').css({
                width: w - 330,
                float: 'left'
            });
            $('iframe').css({
                display: 'inline-block'
            });
        }
        else {
            $('.metro-grid').css({
                width: 'auto',
                float: 'none'
            });
        }
    };
    $(window).resize(function () {
        refresh();
    });
    refresh();
    $('.metro-grid').on('click', 'a', function (e) {
        e.preventDefault();
        $('iframe').attr('src', $(this).attr('href'));
    });
});
