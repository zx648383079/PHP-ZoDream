;
define(["jquery"], function () {
    $("#addCategory").click(function () {
        $("#category").show();
    });
    $("#addWeb").click(function () {
        $("#web").show();
    });
    $("#category").click(function () {
        $(this).hide();
    });
    $("#web").click(function () {
        $(this).hide();
    });
});
//# sourceMappingURL=home.js.map