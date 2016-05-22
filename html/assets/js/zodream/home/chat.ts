/// <reference path="../../../../../typings/jquery/jquery.d.ts" />
;define(["jquery"], function() {
    $(".send").click(function () {
        var content = $(".content").val();
        if (!content) {
            alert("不能为空！");
            return;
        }
        $.post("/chat/send", {
            user: USER,
        }, function (data) {
            if (data.success != "success") {
                alert(data.error);
                return;
            }
            $(".chat").append('<li class="right"><p>' + content +
                '</p><p>' + data.time +
                '</p></li>');
            $(".content").val("")
        }, "json");
    });

    var get = setInterval(function () {
        $.getJSON("/chat/get?user=" + USER +"&time=" + time, function (data) {
            if (data.success != "success") {
                alert("服务器出错");
                return;
            }
            time = data.time;
            if (!data.data) {
                return;
            }
            $(data.data).each(function (i, val) {
                $(".chat").append('<li class="left"><p>' + val.content +
                    '</p><p>' + val.create_at +
                    '</p></li>');
            });
        });
    }, 5000);
});