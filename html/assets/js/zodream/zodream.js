var vue = new Vue({
    el: '#comments',
    data: {
        comments: [],
        more: true,
        page: 1
    },
    methods: {
        getMore: function () {
            getComments();
        },
        submit: function () {
            var postdata = $("#comment-form").serialize();
            $.post(window.location.pathname, postdata, function (data, status) {
                if (status == "success" && data.status == 'success') {
                    alert("评论成功");
                }
            });
        }
    }
});
function getComments() {
    if (!vue.more)
        return;
    $.getJSON(window.location.pathname + "?comments=" + vue.page, function (data, status) {
        if (status == "success") {
            for (var key in data.data) {
                if (data.data.hasOwnProperty(key)) {
                    vue.comments.push(data.data[key]);
                }
            }
            vue.more = data.more;
            vue.page++;
        }
    });
}
getComments();
//# sourceMappingURL=zodream.js.map