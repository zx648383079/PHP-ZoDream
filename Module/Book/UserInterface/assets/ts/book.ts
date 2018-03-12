$(function() {
    $(".tab-switch .item").click(function () { 
        $(this).addClass('active').siblings().removeClass('active');
        $('.rank-switch .bd .list').eq($(this).index()).show().siblings().hide();
    });
});