$(document).ready(function () {
    var frame = $("#mainFrame");
    var changeFrame = function () {
        frame.css("height", frame.parent().height() + 'px');
    };
    changeFrame();
    $(window).resize(function () {
        changeFrame();
    });
    $(document).ajaxStart(function() { Pace.restart(); });
    $(".sidebar-menu a").click(function () {
        var link = $(this).attr('href');
        if (link != "#") {
            frame.attr("src", link);
            return false;
        }
    });
});
