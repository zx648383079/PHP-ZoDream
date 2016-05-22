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
});
//# sourceMappingURL=blog.js.map