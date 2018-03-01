/**
 * post 提交
 * @param url 
 * @param data 
 * @param callback 
 */
let postJson = function(url: string, data: any, callback?: (data: any)=>any) {
    if (typeof data == 'function') {
        callback = data;
        data = {};
    }
    $.post(url, data, callback, 'json');
};
function ajaxForm(url, data, callback?: (data: any)=>any) {
    postJson(url, data, function(data) {
        if (callback) {
            callback(data);
            return;
        }
        parseAjax(data);
    });
}
/**
 * 转化请求响应结果
 * @param data 
 */
let parseAjax = function(data) {
    if (data.code != 200) {
        Dialog.tip(data.errors || '操作执行失败！');
        return;
    }
    Dialog.tip(data.messages || '操作执行完成！');
    if (data.data && data.data.refresh) {
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }
    if (data.data && data.data.url) {
        setTimeout(() => {
            window.location.href = data.data.url;
        }, 500);
    }
};
/**
 * 转化float
 * @param arg 
 */
let toFoat = function (arg) {
    if (!arg) {
        return 0;
    }
    arg = arg.replace(',', '').match(/[\d\.]+/);
    if (!arg) {
        return 0;
    }
    return parseFloat(arg);
};
/**
 * 转化数字
 * @param arg 
 */
let toInt = function (arg) {
    if (!arg) {
        return 0;
    }
    arg = arg.replace(',', '').match(/[\d]+/);
    if (!arg) {
        return 0;
    }
    return parseInt(arg);
};
$(function() {
    $("a[data-type=refresh]").click(function() {
        window.location.reload();
    });
    $("a[data-type=del]").click(function(e) {
        e.preventDefault();
        let tip = $(this).attr('data-tip') || '确定删除这条数据？';
        if (!confirm(tip)) {
            return;
        }
        postJson($(this).attr('href'), function(data) {
            if (data.code == 0 && !data.msg) {
                data.msg = '删除成功！';
            }
            parseAjax(data);
        });
    });
    $("a[data-type=ajax]").click(function(e) {
        e.preventDefault();
        let $this = $(this);
        let successTip = $this.attr('data-success') || '提交成功！';
        let errorTip = $this.attr('data-error') || '提交失败！';
        let callback = $this.attr('data-callback');
        postJson($this.attr('href'), function(data) {
            if (data.code == 0 && !data.msg) {
                data.msg = successTip;
            }
            if (data.code == 0 && callback) {
                eval(callback + '($this, data);');
            }
            parseAjax(data);
        });
    });
    $("form[data-type=ajax]").submit(function() {
        let $this = $(this);
        ajaxForm($this.attr('action'), $this.serialize());
        return false;
    });
    $("a[data-type=post]").click(function(e) {
        e.preventDefault();
        let form = $('<form action="'+ $(this).attr('href') +'" method="post"></form');
        $(document.body).append(form);
        form.submit();
    });
    $(".zd-tab .zd-tab-head .zd-tab-item").click(function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.parents(".zd-tab").find(".zd-tab-body .zd-tab-item").eq($this.index()).addClass("active").siblings().removeClass("active");
    });
    $(".page-tip .toggle").click(function() {
        $(this).parents('.page-tip').toggleClass('min');
    });
    var autoRedirct = function() {
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