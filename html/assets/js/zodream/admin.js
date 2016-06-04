require.config({
    baseUrl: "/assets/js/",
    paths: {
        jquery: "jquery/jquery-2.2.4.min",
        bootstrap: "bootstrap/bootstrap.min",
        admin: "zodream/admin",
        ueditor: '../ueditor/ueditor.all.min',
        codemirror: 'codemirror/codemirror',
        code: 'codemirror',
        bui: "bui/bui"
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
    $("#selectAll").click(function () {
        $("form :checkbox").attr("checked", this.checked);
    });
});
//# sourceMappingURL=admin.js.map