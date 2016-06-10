;define(['jquery', 'cropper', 'logo'], function() {
    $("#choose ul li").click(function() {
        $(this).addClass("active").siblings().removeClass("active");
    });
    $("#choose .btn").click(function() {
        $('#avatar-modal').modal('hide');
        let img = $("#choose ul li.active img");
        if (img) {
            $(".img-rounded").attr("src", img.attr("src"));
            $(".avatar-view input").val(img.attr("src"));
        }
    });
});