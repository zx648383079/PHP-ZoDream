/// <reference path="../../../../typings/requirejs/require.d.ts" />
/// <reference path="../../../../typings/jquery/jquery.d.ts" />
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
            deps: ['/assets/ueditor/third-party/zeroclipboard/ZeroClipboard.min.js','/assets/ueditor/ueditor.config.js'],
            exports: 'UE',
            init:function(ZeroClipboard){
                //导出到全局变量，供ueditor使用
                window.ZeroClipboard = ZeroClipboard;
            }
        }
    }
});

require(["jquery", "bootstrap", "admin/zodream"], function() {
    $(".topMenu li").click(function() {
        let data = $(this).attr("data");
        if (undefined == data) {
            return;
        }
        zodream.main.getMenu(data);
        $(".topMenu li").removeClass("active");
        $(this).addClass("active");
    });
    
    $(".secondMenu li").click(function() {
        zodream.main.navigate($(this).attr("data"), $(this).attr("target"));
    });
    $("#leftMenu").on("click", "li", function(event) {
        if ($(this).has("ul")) {
            let ul = $(this).children("ul");
            if (ul.hasClass("open")) {
                ul.removeClass("open");
            } else {
                ul.addClass("open");
            }
        }
        let data = $(this).attr("data");
        if (data) {
            zodream.main.navigate("admin.php/" + data);
        }
        event.stopPropagation();
    });
    $("#selectAll").click(function() {
       $("form :checkbox").attr("checked", this.checked);
    });
});
