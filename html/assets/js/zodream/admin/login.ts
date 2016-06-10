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
    let openwindow = function(url: string, name: string, iWidth: number, iHeight: number) {
        //window.screen.height获得屏幕的高，window.screen.width获得屏幕的宽
        let iTop = (window.screen.height-30-iHeight)/2; //获得窗口的垂直位置;
        let iLeft = (window.screen.width-10-iWidth)/2; //获得窗口的水平位置;
        window.open(url,name,'height='+iHeight+',,innerHeight='+iHeight+',width='+iWidth+',innerWidth='+iWidth+',top='+iTop+',left='+iLeft+',toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no');
    }
    let check:number;
    $(".oauth a").click(function() {
      openwindow("/account.php/auth/oauth?type=" + $(this).attr("class"), null, 600, 400);

      clearInterval(check);
      check = setInterval(function() {
        $.getJSON("/account.php/auth/check", function(data) {
          if (data.status != "success") {
            return;
          }
          window.location.href = data.url;
        });
      }, 3000);
    });
});