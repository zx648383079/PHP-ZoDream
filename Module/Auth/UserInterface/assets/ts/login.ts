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

});

function bindLogin(baseUrl: string) {
    let is_init = false,
        is_checking = false,
        qr_box = $(".login-form .login-qr-box"),
        refreshQr = function() {
            qr_box.find('.qr-box img').attr('src', baseUrl + 'qr?v=' + Math.random());
            is_checking = true;
            check_loop();
            qr_box.attr('class', 'login-qr-box');
        },
        check_qr = function(cb) {
            $.getJSON(baseUrl + 'qr/check', function(data) {
                if (data.code == 200) {
                    qr_box.attr('class', 'login-qr-box qr_success');
                    is_checking = false;
                    parseAjax(data);
                    return;
                }
                if (data.code == 201) {
                    cb && cb();
                    return;
                }
                if (data.code == 202) {
                    qr_box.attr('class', 'login-qr-box waiting_confirm');
                    cb && cb();
                    return;
                }
                if (data.code == 203) {
                    qr_box.attr('class', 'login-qr-box qr_reject');
                    is_checking = false;
                    return;
                }
                qr_box.attr('class', 'login-qr-box qr_expired');
                is_checking = false;
            });
        }, 
        check_loop = function() {
            if (!is_checking) {
                return;
            }
            setTimeout(() => {
                check_qr(check_loop);
            }, 3000);
        };
    $(".login-form .other-box .fa-qrcode").click(function() {
        $(".login-box").addClass('slided');
        if (!is_init) {
            is_init = true;
            refreshQr();
        }
    });
    qr_box.find(".fa-refresh").click(function() {
        refreshQr();
    });
    qr_box.find(".btn").click(function() {
        $(".login-box").removeClass('slided');
    });
}