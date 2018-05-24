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
        let $this = $(this);
        postJson($this.attr('action'), $this.serialize(), function(data) {
            parseAjax(data);
        });
        return false;
    });
});