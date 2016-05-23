;
define(["jquery"], function () {
    $(".recommend").click(function () {
        var recommend = $(this);
        var data = recommend.attr("data");
        $.getJSON('/blog/recommend/id/' + data, function (data) {
            if (data.status == 'success') {
                var span = recommend.find("span").first();
                span.text(parseInt(span.text()) + 1);
                alert("推荐成功！");
                return;
            }
            alert(data.error);
        });
    });
    $("#comment .reply").click(function () {
        var content = $("#textarea_content"), name = $(this).attr("data"), value = content.val();
        if (value.indexOf("@" + name) < 0) {
            content.val("@" + name + " " + value);
        }
        content.scroll().focus();
    });
});
//# sourceMappingURL=blog.js.map