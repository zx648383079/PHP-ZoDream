require.config({
    baseUrl: "/assets/js/",
    paths: {
        jquery: "jquery/jquery-2.2.3.min",
        bootstrap: "bootstrap/bootstrap.min",
        admin: "zodream/admin",
        ueditor: '../ueditor/ueditor.all.min'
    },
    shim: {
        ueditor: {
            deps: ['/assets/ueditor/third-party/zeroclipboard/ZeroClipboard.min.js', '/assets/ueditor/ueditor.config.js'],
            exports: 'UE',
            init: function (ZeroClipboard) {
                window.ZeroClipboard = ZeroClipboard;
            }
        }
    }
});
require(["jquery", "bootstrap", "admin/zodream"], function () {
    $(".topMenu li").click(function () {
        var data = $(this).attr("data");
        if (undefined == data) {
            return;
        }
        zodream.main.getMenu(data);
        $(".topMenu li").removeClass("active");
        $(this).addClass("active");
    });
    $(".secondMenu li").click(function () {
        zodream.main.navigate($(this).attr("data"), $(this).attr("target"));
    });
    $("#leftMenu").on("click", "li", function (event) {
        if ($(this).has("ul")) {
            var ul = $(this).children("ul");
            if (ul.hasClass("open")) {
                ul.removeClass("open");
            }
            else {
                ul.addClass("open");
            }
        }
        var data = $(this).attr("data");
        if (data) {
            zodream.main.navigate("admin.php/" + data);
        }
        event.stopPropagation();
    });
    $("#selectAll").click(function () {
        $("form :checkbox").attr("checked", this.checked);
    });
});
//# sourceMappingURL=admin.js.map