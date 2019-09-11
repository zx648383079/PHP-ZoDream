$(function() {
	if (!$(window.parent.document.body).hasClass('login-page')) {
		window.parent.location.href = window.location.href;
		return;
    }
    $('.login-form').on('focus', '.input-group input', function() {
        $(this).closest('.input-group').addClass('input-focus').removeClass('error');
    }).on('blur', '.input-group input', function() {
        $(this).closest('.input-group').removeClass('input-focus');
    }).submit(function() {
        let _this = $(this);
        if (_this.find('.error').length > 0) {
            Dialog.tip('您填写的信息不正确!');
            return false;
        }
        let loading = Dialog.loading();
        postJson(_this.attr('action'), _this.serialize(), function(data) {
            loading.close();
            parseAjax(data);
        });
        return false;
    });

});

function bindRegister(baseUrl: string) {
    $('.login-form .input-group input').blur(function() {
        let _this = $(this);
        let box = _this.closest('.input-group');
        let name = _this.attr('name');
        let val = _this.val().toString().trim();
        if (val.length < 1) {
            box.addClass('error');
            return;
        }
        if (name === 'name' || name === 'email') {
            postJson(baseUrl + 'check', {
                name,
                value: val
            }, data => {
                if (data.code == 200 && data.data) {
                    box.addClass('error');
                    Dialog.tip((name == 'name' ? '昵称' : '邮箱') + '已注册');
                    return;
                }
            });
            return;
        }
        if (name === 'rePassword' && val !== $('.login-form input[name="password"]').val()) {
            box.addClass('error');
            Dialog.tip('两次密码不一致');
            return;
        }
    });
}

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
    qr_box.find(".fa-sync").click(function() {
        refreshQr();
    });
    qr_box.find(".btn").click(function() {
        $(".login-box").removeClass('slided');
    });
    let credentialCreationOptionsLogin = {
        mediation: 'required',
        publicKey: {
            userVerification: 'required',
            challenge: new Uint8Array(16),
            timeout: 60 * 1000,
        },
    };
    $('.login-webauth').click(function() {
        if (!navigator.credentials) {
            return;
        }
        navigator.credentials.get(credentialCreationOptionsLogin)
        .then((credentials) => {
            console.log(credentials);
        })
        .catch(console.error);
    }).toggle(!!navigator.credentials);
}