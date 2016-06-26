require.config({
    baseUrl: "/assets/js/",
    paths: {
        jquery: "jquery/jquery-3.0.0.min",
        bootstrap: "bootstrap/bootstrap.min",
        knockout: "knockout/knockout-3.4.0",
        flexslider: "jquery/jquery.flexslider",
        admin: "zodream/admin",
        home: "zodream/home",
    }
});
require(['jquery', 'bootstrap'], function () {
    $(".fixed-slide li").hover(function () {
        $(this).find(".box").stop().animate({ "width": "124px" }, 200).css({ "opacity": "1", "filter": "Alpha(opacity=100)", "background": "#ae1c1c" });
    }, function () {
        $(this).find(".box").stop().animate({ "width": "54px" }, 200).css({ "opacity": "0.8", "filter": "Alpha(opacity=80)", "background": "#000" });
    });
    $(".fixed-slide .toTop").click(function () {
        $('html,body').animate({ 'scrollTop': 0 }, 600);
    });
});
//# sourceMappingURL=home.js.map