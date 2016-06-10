;
define(["jquery", "flexslider"], function () {
    $('.flexslider').flexslider({
        animation: "slide",
        start: function (slider) {
            $('body').removeClass('loading');
        }
    });
    var count = $(".pics ul li").length, width = $(".pics").width() - (count - 1) * 100;
    $(".pics ul li").last().css("width", width);
    $(".pics ul li").hover(function () {
        $(this).stop(true).animate({ width: width + "px" }, 500).siblings().stop(true).animate({ width: "100px" }, 500);
    });
});
//# sourceMappingURL=home.js.map