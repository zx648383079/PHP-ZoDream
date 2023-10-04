declare var BASE_URI: string;
declare var UPLOAD_URI: string;
// declare var file_upload: any;

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
    $.post(url, data, callback || parseAjax, 'json');
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
    if (data.code === 302 || (data.code === 401 && data.url)) {
        window.location.href = data.url;
        return;
    }
    if (data.code !== 200) {
        if (typeof data.message === 'object') {
            $.each(data.message, (i, v) => {
                $.each(v, (j, m) => {
                    Dialog.tip(i + (m || '操作执行失败！'));
                });
            });
            return;
        }
        Dialog.tip(data.message || '操作执行失败！');
        return;
    }
    Dialog.tip(data.message || '操作执行完成！');
    if (data.data && data.data.refresh) {
        setTimeout(() => {
            if (!data.data.no_jax && typeof parseAjaxUri == 'function') {
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
            if (!data.data.no_jax && typeof parseAjaxUri == 'function') {
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

let file_upload: any;

$(function() {
    if (typeof Upload === 'function' && typeof UPLOAD_URI === 'string') {
        file_upload = new Upload(null, {
            url: UPLOAD_URI,
            name: 'upfile',
            template: '{url}',
            onbefore: function(data: any, element: JQuery) {
                element.closest('.file-input').addClass('file-uploading');
                return data;
            },
            onafter: function(data: any, element: JQuery) {
                if (data.state == 'SUCCESS') {
                    element.prev('input').val(data.url);
                } else if (data.code === 302) {
                    location.href = data.url;
                } else {
                    Dialog.tip(data.state ?? '上传失败');
                }
                element.closest('.file-input').removeClass('file-uploading');
                return false;
            },
            onerror: function(error: any, element: JQuery) {
                Dialog.tip(error.status == 413 ? '文件太大，无法上传' :  '上传失败');
                element.closest('.file-input').removeClass('file-uploading');
            }
        });
    }
    const doc = $(document).on('click', "a[data-type=refresh]", function() {
        window.location.reload();
    })
    .on('click', "a[data-type=ajax]", function(e) {
        e.preventDefault();
        let $this = $(this);
        let successTip = $this.data('success') || '提交成功！';
        let errorTip = $this.data('error') || '提交失败！';
        let callback = $this.data('callback');
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
        form.trigger('submit');
    })
    .on('click', ".file-input [data-type=upload]", function() {
        const that = $(this);
        const box = that.closest('.file-input');
        if (box.hasClass('file-uploading')) {
            Dialog.tip('文件正在上传中。。。');
            return;
        }
        const filter: string = that.data('allow') || '';
        file_upload.options.filter = filter;
        if (filter.indexOf('image') < 0) {
            file_upload.options.url = UPLOAD_URI.replace('uploadimage', 'uploadfile');
        }
        file_upload.start(that);
    })
    .on('click', ".file-input [data-type=preview]", function() {
        const box = $(this).closest('.file-input');
        const filter = box.find('[data-type=upload]').data('allow') || '';
        const isImage = filter.indexOf('image') >= 0;
        const fileUrl = box.find('input').val() as any;
        if (!fileUrl) {
            Dialog.tip(`请上传${isImage?'图片':'文件'}！`);
            return;
        }
        if (!isImage) {
            window.open(fileUrl, '_blank');
            return;
        }
        const target = new Image;
        target.src = fileUrl;
        target.onload = () => {
            const modal = Dialog.box({
                title: '预览',
                content: ''
            });
            if (target.width > window.innerWidth || target.height > window.innerHeight) {
                const scale = Math.min((window.innerWidth - 100) / target.width, (window.innerHeight - 100) / target.height);
                target.style.width = scale * target.width + 'px';
                target.style.height = scale * target.height + 'px';
            }
            modal.find('.dialog-body').append(target);
            modal.showCenter();
        };
    })
    .on('click', ".tab-box .tab-header .tab-item", function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        let tab = $this.closest(".tab-box").find(".tab-body .tab-item").eq($this.index()).addClass("active");
        tab.siblings().removeClass("active");
        tab.trigger('tabActived', $this.index());
    });
});