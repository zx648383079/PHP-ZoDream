/// <reference path="../../../../typings/requirejs/require.d.ts" />
/// <reference path="../../../../typings/jquery/jquery.d.ts" />
require.config({
    baseUrl: "/assets/js/",
    paths: {
        jquery: "jquery/jquery-3.0.0.min",
        bootstrap: "bootstrap/bootstrap.min",
        admin: "zodream/admin",
        ueditor: '../ueditor/ueditor.all.min',
        codemirror: 'codemirror/codemirror',
        cropper: 'jquery/cropper.min',
        logo: 'jquery/sitelogo',
        completer: 'jquery/completer.min',
        code: 'codemirror',
        bui: "bui/bui",
        chart: "chart/Chart.min"
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
    $("#selectAll").click(function() {
       $("form :checkbox").attr("checked", this.checked);
    });
});
