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
    var openwindow = function (url, name, iWidth, iHeight) {
        var iTop = (window.screen.height - 30 - iHeight) / 2;
        var iLeft = (window.screen.width - 10 - iWidth) / 2;
        window.open(url, name, 'height=' + iHeight + ',,innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no');
    };
    var check;
    $(".oauth a").click(function () {
        openwindow("/account.php/auth/oauth?type=" + $(this).attr("class"), null, 600, 400);
        clearInterval(check);
        check = setInterval(function () {
            $.getJSON("/account.php/auth/check", function (data) {
                if (data.status != "success") {
                    return;
                }
                window.location.href = data.url;
            });
        }, 3000);
    });
});
//# sourceMappingURL=login.js.map