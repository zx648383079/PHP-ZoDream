/// <reference path="../../../../../typings/jquery/jquery.d.ts" />
;define(["jquery", "flexslider"], function() {
    $('.flexslider').flexslider({
        animation: "slide",
        start: function(slider){
          $('body').removeClass('loading');
        }
    });
    let width = $(".pics").width() - 300;
    $(".pics ul li").last().css("width", width);
    $(".pics ul li").hover(function(){
        $(this).stop(true).animate({width: width + "px"},500).siblings().stop(true).animate({width:"100px"},500);
    });
});