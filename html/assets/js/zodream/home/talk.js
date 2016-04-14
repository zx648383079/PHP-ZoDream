;
define(["jquery"], function () {
    $(".talk .year .list").each(function (e, target) {
        var $target = $(target), $ul = $target.find("ul");
        $target.height($ul.outerHeight()), $ul.css("position", "absolute");
    });
    $(".talk .year>h2>a").click(function (e) {
        e.preventDefault();
        $(this).parents(".year").toggleClass("closed");
    });
});
//# sourceMappingURL=talk.js.map