/// <reference path="../../../../../typings/requirejs/require.d.ts" />
/// <reference path="../../../../../typings/jquery/jquery.d.ts" />
/// <reference path="../../../../../typings/vue/vue.d.ts" />
;define(["jquery"], function() {
    var verify: string = $("#verify").attr("src");
    $("#verify").click(function() {
       $(this).attr("src", verify + "?" + Math.random());
    });
});