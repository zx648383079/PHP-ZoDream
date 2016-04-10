;
define(["jquery", "fun"], function () {
    $("#search").click(function () {
        zodream.main.search();
    });
    $("#p").keyup(function (event) {
        if (event.keyCode == 13) {
            zodream.main.search();
        }
    });
    $("#addCategory").click(function () {
        $("#category").show();
    });
    $("#addWeb").click(function () {
        $("#web").show();
    });
    $(".dialog").click(function (event) {
        if ($(event.target).is('.dialog-close') || $(event.target).is('.dialog')) {
            event.preventDefault();
            $(this).hide();
        }
    });
});
//# sourceMappingURL=home.js.map