/// <reference path="../../../../typings/requirejs/require.d.ts" />
/// <reference path="../../../../typings/jquery/jquery.d.ts" />
require.config({
    baseUrl: "/assets/js/",
    paths: {
        jquery: "jquery/jquery-2.2.2.min",
        bootstrap: "bootstrap/bootstrap.min",
        vue: "vue/vue.min",
        empire: "zodream/empire",
    }
});

require(["jquery", "empire/fun"], function() {
    $(".topMenu li").click(function() {
        let data = $(this).attr("data");
        if (undefined == data) {
            return;
        }
        zodream.main.getMenu(data);
        $(".topMenu li").removeClass("active");
        $(this).addClass("active");
    });
    
    $(".secondMenu li").click(function() {
        zodream.main.navigate($(this).attr("data"), $(this).attr("target"));
    });
    $("#leftMenu").on("click", "li", function() {
        let data = $(this).attr("data");
        if (data) {
            zodream.main.navigate("admin.php/" + data);
        }
    });
    $("#selectAll").click(function() {
       $("form :checkbox").attr("checked", this.checked);
    });
});
