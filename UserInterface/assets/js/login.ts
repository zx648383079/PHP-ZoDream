$(document).ready(function() {
	if (!$(window.parent.document.body).hasClass('login-page')) {
		window.parent.location.href = window.location.href;
		return;
	}
    $(".login-form").submit(function() {
        let $this = $(this);
        $.post($this.attr('action'), $this.serialize(), function(data) {
            parseAjax(data);
        }, 'json');
        return false;
    });
    $(".register-form").validate({
		rules: {
			mobile: {
				required: true,
				isMobile: true,
				remote: '/login/check?register=1'
			},
			password: {
				required: true,
                minlength: 5
			},
            password_confirmation: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            }

		},
		messages: {
			mobile: {
				required: '请输入手机号',
				isMobile: '手机号格式不正确',
				remote: '此手机号已注册'
			},
            password: {
                required: "请输入密码",
                minlength: "密码长度不能小于 5 个字母"
            },
            password_confirmation: {
                required: "请输入密码",
                minlength: "密码长度不能小于 5 个字母",
                equalTo: "两次密码输入不一致"
            },
		},
        errorPlacement : function(error, element) {
            element.after(error);
        },
        submitHandler: function(form) {
            var loading = Dialog.loading();
            $(form).ajaxSubmit({
                success: function(data) {
                    if (typeof data != 'object') {
                        data = JSON.parse(data);
                    }
                    loading.close();
                    if (data.code == 0) {
                        data.msg = '注册成功！';
                    }
					if (!data.msg) {
						data.msg = '注册失败，请验证您的注册信息是否正确！';
					}
                    Dialog.tip(data.msg);
                    if (data.data) {
                        setTimeout(function() {
                            if (data.data.url) {
                                window.location.href = data.data.url;
                                return;
                            }
							window.location.href = '/';
                        }, 1000);
                    }
                }
            });
            return false;
        }
    });

    $(".sendCode").click(function() {
        let $this = $(this);
        let type = $(this).attr('data-type') || 'register';
        if ($this.hasClass('disable')) {
            return;
        }
        if ($(".ziphone").hasClass('error')) {
            Dialog.tip('请输入有效的手机号码！');
            return;
        }
        let phone = $(".ziphone").val();
        if (!phone || phone.length < 11) {
            Dialog.tip('请输入有效的手机号码！');
            return;
        }
        $.post('/sms/send/' + type, {
            _token: $('meta[name="csrf-token"]').attr('content'),
            mobile: phone,
        }, function(data) {
            if (data.code == 0) {
                Dialog.tip('验证码已发送至 ' + phone +' ，请注意查收');
                $this.addClass('disable').addClass('sent');
                time = 60;
                refreshTime();
                return;
            }
            Dialog.tip(data.msg);
        }, 'json');
    });

    let time = 60;
    let refreshTime = function() {
        time --;
        if (time < 1) {
            $(".sendCode").val('重新发送验证码').removeClass('disable');
            return;
        }
        $(".sendCode").val(time);
        setTimeout(refreshTime, 1000);
    };
});