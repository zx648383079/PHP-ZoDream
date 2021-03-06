declare var BASE_URI: string;
declare var UPLOAD_URI: string;

interface IResponse {
    code: number,
    status: string,
    message?: string|Array<any>,
    data?: any,
    url?: string,
}
/**
 * post 提交
 * @param url 
 * @param data 
 * @param callback 
 */
function postJson(url: string, data: any, callback?: (data: IResponse)=>any) {
    if (typeof data == 'function') {
        callback = data;
        data = {};
    }
    $.post(url, data, callback, 'json');
};
function ajaxForm(url, data, callback?: (data: IResponse)=>any) {
    postJson(url, data, function(data) {
        if (callback) {
            callback(data);
            return;
        }
        parseAjax(data);
    });
}

function formData(item: JQuery): string {
    let data = [];
    item.find('input,textarea,select').each(function(this: HTMLInputElement) {
        if (this.type && ['radio', 'checkbox'].indexOf(this.type) >= 0 && !this.checked) {
            return;
        }
        data.push(encodeURIComponent( this.name ) + '=' +
				encodeURIComponent( $(this).val().toString() ))
    });
    return data.join('&');
}
/**
 * 转化请求响应结果
 * @param data 
 */
function parseAjax(data: IResponse) {
    if (data.code == 302) {
        window.location.href = data.url;
        return;
    }
    if (data.code != 200) {
        Dialog.tip(data.message || '操作执行失败！');
        return;
    }
    Dialog.tip(data.message || '操作执行完成！');
    if (data.data && data.data.refresh) {
        setTimeout(() => {
            if (typeof parseAjaxUri == 'function') {
                parseAjaxUri(window.location.href);
                return;
            }
            window.location.reload();
        }, 500);
    }
    if (data.data && data.data.url) {
        setTimeout(() => {
            if (data.data.url === -1) {
                history.go(-1);
                return;
            }
            if (typeof parseAjaxUri == 'function') {
                parseAjaxUri(data.data.url);
                return;
            }
            window.location.href = data.data.url;
        }, 500);
    }
};
/**
 * 转化float
 * @param arg 
 */
let toFloat = function (arg: any) {
    if (!arg) {
        return 0;
    }
    arg = arg.toString().replace(',', '').match(/[\d\.]+/);
    if (!arg) {
        return 0;
    }
    return parseFloat(arg);
};
/**
 * 转化数字
 * @param arg 
 */
let toInt = function (arg: any) {
    if (!arg) {
        return 0;
    }
    arg = arg.toString().replace(',', '').match(/[\d]+/);
    if (!arg) {
        return 0;
    }
    return parseInt(arg, 10);
};

let strFormat = function(arg: string, ...args: any[]) {
    return arg.replace(/\{(\d+)\}/g, function(m,i) {
        return args[i];
    });
}

$(function() {
    if (typeof Upload === 'function' && typeof UPLOAD_URI === 'string') {
        let file_upload = new Upload(null, {
            url: UPLOAD_URI,
            name: 'upfile',
            template: '{url}',
            onafter: function(data: any, element: JQuery) {
                if (data.state == 'SUCCESS') {
                    element.prev('input').val(data.url);
                } else if (data.code === 302) {
                    location.href = data.url;
                }
                return false;
            }
        });
    }
    $(document).on('click', "a[data-type=refresh]", function() {
        window.location.reload();
    })
    .on('click', "a[data-type=del]", function(e) {
        e.preventDefault();
        let tip = $(this).attr('data-tip') || '确定删除这条数据？';
        if (!confirm(tip)) {
            return;
        }
        let loading = Dialog.loading();
        postJson($(this).attr('href'), function(data) {
            loading.close();
            if (data.code == 200 && !data.msg) {
                data.msg = '删除成功！';
            }
            parseAjax(data);
        });
    })
    .on('click', "a[data-type=ajax]", function(e) {
        e.preventDefault();
        let $this = $(this);
        let successTip = $this.attr('data-success') || '提交成功！';
        let errorTip = $this.attr('data-error') || '提交失败！';
        let callback = $this.attr('data-callback');
        let loading = Dialog.loading();
        postJson($this.attr('href'), function(data) {
            loading.close();
            if (data.code == 200 && !data.msg) {
                data.msg = successTip;
            }
            if (data.code == 200 && callback) {
                eval(callback + '($this, data);');
            }
            parseAjax(data);
        });
    })
    .on('submit', "form[data-type=ajax]", function() {
        let $this = $(this);
        let loading = Dialog.loading();
        ajaxForm($this.attr('action'), $this.serialize(), res => {
            loading.close();
            parseAjax(res);
        });
        return false;
    })
    .on('click', "a[data-type=post]", function(e) {
        e.preventDefault();
        let form = $('<form action="'+ $(this).attr('href') +'" method="post"></form');
        $(document.body).append(form);
        form.submit();
    })
    .on('click', ".file-input [data-type=upload]", function() {
        let that = $(this);
        file_upload.options.filter = that.data('allow') || '';
        file_upload.start(that);
    })
    .on('click', ".file-input [data-type=preview]", function() {
        let img = $(this).parents('.file-input').find('input').val();
        if (!img) {
            Dialog.tip('请上传图片！');
            return;
        }
        Dialog.box({
            title: '预览',
            content: '<img src="'+ img +'">'
        });
    })
    .on('click', ".zd-tab .zd-tab-head .zd-tab-item", function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        let tab = $this.closest(".zd-tab").find(".zd-tab-body .zd-tab-item").eq($this.index()).addClass("active");
        tab.siblings().removeClass("active");
        tab.trigger('tabActived', $this.index());
    }).on('click', ".tab-box .tab-header .tab-item", function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        let tab = $this.closest(".tab-box").find(".tab-body .tab-item").eq($this.index()).addClass("active");
        tab.siblings().removeClass("active");
        tab.trigger('tabActived', $this.index());
    })
    .on('click', ".page-tip .toggle", function() {
        $(this).closest('.page-tip').toggleClass('min');
    });
    $('.sidebar-container .sidebar-container-toggle').click(function() {
        let box = $(this).closest('.app-wrapper').toggleClass('wrapper-min');
        if (box.find('ul').height() < $(window).height() - 100) {
            box.toggleClass('sidebar-fixed', box.hasClass('wrapper-min'));
        }
        $(window).trigger('resize');
    });
    $('.sidebar-container li a').click(function() {
        let $this = $(this),
            box = $this.closest('li');
        if (box.find('ul').length > 0) {
            box.toggleClass('expand');
            return;
        }
        $('.sidebar-container li').removeClass('active');
        box.addClass('active');
        $this.closest('.app-wrapper').removeClass('wrapper-min');
    });
    let autoRedirct = function() {
        let ele = $(".autoRedirct");
        if (ele.length < 1) {
            return;
        }
        let url = ele.attr('href') || ele.attr('data-url');
        let text = ele.text();
        let time: number = toInt(ele.attr('data-time'));
        if (time < 1) {
            time = 3;
        }
        ele.text(text + '(' +time +' 秒)');
        let handle = setInterval(function() {
            time --;
            ele.text(text + '(' +time +' 秒)');
            if (time <= 0) {
                clearInterval(handle);
                ele.text(text + '(正在跳转...)');
                window.location.href = url;
            }
        }, 1000);
    };
    autoRedirct();
});