/// <reference path="../../../../../typings/jquery/jquery.d.ts" />
;define(["jquery"], function() {
    $(".recommend").click(function() {
        let recommend = $(this);
        let data = recommend.attr("data");
        $.get('/blog/recommend/id/' + data, function(data) {
            if (data['status'] == 'success') {
                let span = recommend.find("span").first();
                span.text(parseInt(span.text()) + 1);
                alert("推荐成功！");
            }
        });
    })
});