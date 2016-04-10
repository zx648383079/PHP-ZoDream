require.config({
    baseUrl: "/assets/js/",
    paths: {
        jquery: "jquery/jquery-2.2.2.min",
        bootstrap: "bootstrap/bootstrap.min",
        vue: "vue/vue.min",
        empire: "zodream/empire",
    }
});
require(["jquery", "empire/fun"], function () {
    $(".topMenu li").click(function () {
        zodream.main.getMenu($(this).attr("data"));
        $(".topMenu li").removeClass("active");
        $(this).addClass("active");
    });
    $(".secondMenu li").click(function () {
        zodream.main.navigate($(this).attr("data"), $(this).attr("target"));
    });
    $("#leftMenu").on("click", "li", function () {
        var data = $(this).attr("data");
        if (data) {
            zodream.main.navigate(data);
        }
    });
    $("#selectAll").click(function () {
        $("form :checkbox").attr("checked", this.checked);
    });
});
//# sourceMappingURL=main.js.map