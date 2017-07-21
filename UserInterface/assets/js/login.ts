$(document).ready(function() {
	if (!$(window.parent.document.body).hasClass('login-page')) {
		window.parent.location.href = window.location.href;
		return;
	}
    $.extend($.validator.messages, {
        required: "这是必填字段",
        remote: "请修正此字段",
        email: "请输入有效的电子邮件地址",
        url: "请输入有效的网址",
        date: "请输入有效的日期",
        dateISO: "请输入有效的日期 (YYYY-MM-DD)",
        number: "请输入有效的数字",
        digits: "只能输入数字",
        creditcard: "请输入有效的信用卡号码",
        equalTo: "你的输入不相同",
        extension: "请输入有效的后缀",
        maxlength: $.validator.format("最多可以输入 {0} 个字符"),
        minlength: $.validator.format("最少要输入 {0} 个字符"),
        rangelength: $.validator.format("请输入长度在 {0} 到 {1} 之间的字符串"),
        range: $.validator.format("请输入范围在 {0} 到 {1} 之间的数值"),
        max: $.validator.format("请输入不大于 {0} 的数值"),
        min: $.validator.format("请输入不小于 {0} 的数值")
    });
    $.validator.addMethod("isMobile", function(value, element) {
        var length = value.length;
        var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
        return this.optional(element) || (length == 11 && mobile.test(value));
    }, "请正确填写您的手机号码");
    $(".login-form").validate({
		rules: {
			mobile: {
				required: true,
				isMobile: true,
				remote: '/login/check'
			},
			password: {
				required: true
			}
		},
		messages: {
			mobile: {
				required: '请输入手机号',
				isMobile: '手机号格式不正确',
				remote: '此手机号未注册'
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
                        data.msg = '登录成功！';
                    }
					if (!data.msg) {
						data.msg = '登录失败，请验证您的账号和密码是否正确！';
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
                },
                error: function(xhr, err, errMsg, form: JQuery) {
                    loading.close();
                    let error = '';
                    $.each(xhr.responseJSON, (name, text)=>{
                        error = text;
                    });
                    Dialog.tip(error);
                }
            });
            return false;
        }
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