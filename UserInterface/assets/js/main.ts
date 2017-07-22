let parseAjax = function(data) {
    if (data.code == 200 && !data.message) {
        data.message = '执行成功！';
    }
    Dialog.tip(data.message);
    if (data.data) {
        setTimeout(function() {
            if (data.data.url) {
                window.location.href = data.data.url;
                return;
            }
            if (data.data.refresh) {
                window.location.reload();
            }
            if (data.data.back) {
                window.history.back();
            }
        }, 1000);
    }
};
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
class Uri {
    constructor(
        private _path: string = '',
        private _data: {[key: string]: string | number} = {}
    ) {
        if (_path.indexOf('?') >= 0) {
            [this._path, this._data] = this._parseUrl(_path);
        }
    }

    public setData(key: any, val?: string | number): this {
        if (typeof key == 'object') {
            this._data = $.extend(this._data, key);
        } else {
            this._data[key] = val;
        }
        return this;
    }

    public removeKey(...key: string[]): this {
        key.forEach(item =>{
            delete this._data[item];
        })
        return this;
    }

    public clearData(): this {
        this._data = {};
        return this;
    }

    public get(success?: (data: any, textStatus: string, jqXHR: JQueryXHR) => any, dataType?: string) {
        $.get(this.toString(), success, dataType);
    }

    public getJson(success?: (data: any, textStatus: string, jqXHR: JQueryXHR) => any) {
        $.getJSON(this.toString(), success);
    }

    public post(data?: Object|string, success?: (data: any, textStatus: string, jqXHR: JQueryXHR) => any, dataType?: string) {
        $.post(this.toString(), data, success, dataType);
    }

    public static parse(url: string | Uri): Uri {
        if (typeof url == 'object') {
            return url;
        }
        return new Uri(url);
    }

    public toString() {
        let param = Uri.getData(this._data);
        if (param == '') {
            return this._path;
        }
        return this._path + '?' + param;
    }

    public static getData(args: any): string {
        if ('object' != typeof args) {
            return args;
        }
        let value: string = '';
        $.each(args, function(key, item) {
            value += Uri._filterValue(item, key);
        });
        return value.substring(0, value.length - 1);
    }

    public static _filterValue(data, pre: string | number) {
        if (typeof data != 'object') {
            return pre + "=" + data + "&";
        }
        let value = '';
        let isArray: boolean = data instanceof Array;
        $.each(data, function(key, item) {
            value += Uri._filterValue(item, pre + (isArray ? "[]" : "[" + key + "]"));
        });
        return value;
    }

    private _parseUrl(url: string): [string, {[key: string]: string}] {
        let [path, param] = url.split('?', 2);
        if (!param) {
            return [path, {}];
        }
        let ret = {},
          seg = param.split('&'),
          len = seg.length, i = 0, s; //len = 2
        for (; i < len; i++) {
            if (!seg[i]) { 
              continue; 
            }
            s = seg[i].split('=');
            ret[s[0]] = s[1];
        }
        return [path, ret];
    }

    public static remove(...key: string[]) {
        window.location.replace(this.parse(window.location.href).removeKey(...key).toString());
    }

    public static replace(key: any, value?: string| number) {
        window.location.replace(this.parse(window.location.href).setData(key, value).toString());
    }
}
$(document).ready(function() {
    $(".exportA").click(function(e) {
        e.preventDefault();
        let url = $(this).attr('href') || Uri.parse(window.location.href).setData('export', 1).toString();
        window.open(url, '_blank')
    });
    $(".refreshA").click(function() {
        window.location.reload();
    });
    $(".postA").click(function(e) {
        e.preventDefault();
        let form = $('<form action="'+ $(this).attr('href') +'" method="post"></form');
        form.append('<input type="hidden" name="_token" value="' + $('meta[name="csrf-token"]').attr('content') +'">');
        $(document.body).append(form);
        form.submit();
    });
    $(".delA").click(function(e) {
        e.preventDefault();
        let tip = $(this).attr('data-tip') || '确定删除这条数据？';
        if (!confirm(tip)) {
            return;
        }
        $.post($(this).attr('href'), {
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(data) {
            if (data.code == 0 && !data.msg) {
                data.msg = '删除成功！';
            }
            parseAjax(data);
        }, 'json');
    });
    $(".ajaxA").click(function(e) {
        e.preventDefault();
        let successTip = $(this).attr('data-success') || '提交成功！';
        let errorTip = $(this).attr('data-error') || '提交失败！';
        let callback = $(this).attr('data-callback');
        $.post($(this).attr('href'), {
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(data) {
            if (data.code == 0 && !data.msg) {
                data.msg = successTip;
            }
            if (data.code == 0 && callback) {
                eval(callback + '();');
            }
            parseAjax(data);
        }, 'json');
    });
    $(".number-box .number-minus").click(function() {
        let numEle = $(this).parents('.number-box').find('.number');
        let num = toInt(numEle.val());
        numEle.val(Math.max(num - 1, toInt(numEle.attr('min'))));
        numEle.trigger('change');
    });
    $(".number-box .number-plus").click(function() {
        let numEle = $(this).parents('.number-box').find('.number');
        let num = toInt(numEle.val()) + 1;
        let max = numEle.attr('max');
        if (max) {
            num = Math.min(num, toInt(max));
        }
        numEle.val(num);
        numEle.trigger('change');
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