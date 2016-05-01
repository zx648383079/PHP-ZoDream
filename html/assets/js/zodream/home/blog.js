;
define(["jquery"], function () {
    $(".recommend").click(function () {
        var recommend = $(this);
        var data = recommend.attr("data");
        $.get('/blog/recommend/id/' + data, function (data) {
            if (data['status'] == 'success') {
                var span = recommend.find("span").first();
                span.text(parseInt(span.text()) + 1);
                alert("推荐成功！");
            }
        });
    });
});
//# sourceMappingURL=blog.js.map