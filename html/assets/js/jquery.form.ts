enum FormStatus {
    None,
    Posting,
    Posted
}

enum InitStatus {
    None,
    Inited
}

class Validator {
    public static rules: any = {
        "*": {
            pattern: /[\w\W]+/,
            message: '内容不能为空'
        },
        m: {
            pattern: /^13[\d]{9}$|^14[57]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0678]{1}\d{8}$|^18[\d]{9}$/,
            message: '请输入有效的手机号码'
        },
        e: {
            pattern: /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
            message: '请输入有效的邮箱'
        },
        u: {
            pattern: /^(\w+:\/\/)?\w+(\.\w+)+.*$/,
            message: '请输入有效的网址'
        },
        i: {
            pattern: /^\d+$/,
            message: '请输入有效的数字'
        }
    };

    public static getMessage(msg: string, name?: string, value?: string) {
        return msg.replace("{n}", name).replace("{v}", value);
    }


}

class Form {
    constructor(
        public element: JQuery,
        option?: FormOptions
    ) {
        this.options = $.extend({}, new FormDefaultOptions(), Option);
    }

    public options: FormOptions;

    public init() {
        if (InitStatus.Inited == this.element.FormInit) {
            return;
        }
        
    }


}

interface FormOptions {
    error?: (msg: string, element: JQuery) => void | string,
    submit?: Function,
    success?: Function,
    submitTag?: string
}

class FormDefaultOptions implements FormOptions {

}

 ;(function($: any) {
  $.fn.form = function(options?: FormOptions) {
    return new Form(this, options); 
  };
})(jQuery);