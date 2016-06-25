;
define(['jquery'], function () {
    $("#clear").click(function () {
        var clear = confirm("您将执行清除所有缓存！");
        if (clear != true) {
            return;
        }
        $.post(null, function (data) {
            if (data.status == "success") {
                alert("已清除所有缓存！");
            }
        }, "json");
    });
    if (URLS == undefined) {
        return;
    }
    $(URLS).each(function (index, item) {
        $.get(item.url, function (data) {
            var tag = '<span class="text-danger">X</span>';
            if (data) {
                tag = '<span class="text-primary">√</span>';
            }
            $("#cache").append('<tr><td>' + item.name + '</td><td>' + tag + '</td></tr>');
            if (index == URLS.length - 1) {
                $("#message").text(" 更新缓存完成！");
            }
        });
    });
});
//# sourceMappingURL=cache.js.map