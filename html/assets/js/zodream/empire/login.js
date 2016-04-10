;
define(["jquery"], function () {
    var verify = $("#verify").attr("src");
    $("#verify").click(function () {
        $(this).attr("src", verify + "?" + Math.random());
    });
});
//# sourceMappingURL=login.js.map