$(function() {
	if (!$(window.parent.document.body).hasClass('login-page')) {
		window.parent.location.href = window.location.href;
		return;
    }
    $(".login-form .input-group input").focus(function() {
        $(this).parents('.input-group').addClass('input-focus');
    }).blur(function() {
        $(this).parents('.input-group').removeClass('input-focus');
    });
    $(".login-form").submit(function() {
        let _this = $(this),
            loading = Dialog.loading();
        postJson(_this.attr('action'), _this.serialize(), function(data) {
            parseAjax(data);
        });
        return false;
    });
    $(".login-form .other-box .fa-qrcode").click(function() {
        $(".login-box").addClass('slided');
    });
    $(".login-form .login-qr-box .btn").click(function() {
        $(".login-box").removeClass('slided');
    });
});