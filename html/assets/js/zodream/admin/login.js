;
define(["jquery", "completer"], function () {
    var verify = $("#verify").attr("src");
    $("#verify").click(function () {
        $(this).attr("src", verify + "?" + Math.random());
    });
    $("#email").completer({
        separator: "@",
        source: ["163.com", "qq.com", "126.com", "139.com", "gmail.com", "hotmail.com", "icloud.com"]
    });
});
//# sourceMappingURL=login.js.map