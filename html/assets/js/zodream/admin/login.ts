/// <reference path="../../../../../typings/requirejs/require.d.ts" />
/// <reference path="../../../../../typings/jquery/jquery.d.ts" />
/// <reference path="../../../../../typings/vue/vue.d.ts" />
;define(["jquery", "completer"], function() {
    var verify: string = $("#verify").attr("src");
    $("#verify").click(function() {
       $(this).attr("src", verify + "?" + Math.random());
    });
    $("#email").completer({
		separator: "@",
		source: ["163.com", "qq.com", "126.com", "139.com", "gmail.com", "hotmail.com", "icloud.com"]
	});
});