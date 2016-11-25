var FormStatus;
(function (FormStatus) {
    FormStatus[FormStatus["None"] = 0] = "None";
    FormStatus[FormStatus["Posting"] = 1] = "Posting";
    FormStatus[FormStatus["Posted"] = 2] = "Posted";
})(FormStatus || (FormStatus = {}));
var InitStatus;
(function (InitStatus) {
    InitStatus[InitStatus["None"] = 0] = "None";
    InitStatus[InitStatus["Inited"] = 1] = "Inited";
})(InitStatus || (InitStatus = {}));
var Validator = (function () {
    function Validator() {
    }
    Validator.getMessage = function (msg, name, value) {
        return msg.replace("{n}", name).replace("{v}", value);
    };
    Validator.rules = {
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
    return Validator;
}());
var Form = (function () {
    function Form(element, option) {
        this.element = element;
        this.options = $.extend({}, new FormDefaultOptions(), Option);
    }
    Form.prototype.init = function () {
        if (InitStatus.Inited == this.element.FormInit) {
            return;
        }
    };
    return Form;
}());
var FormDefaultOptions = (function () {
    function FormDefaultOptions() {
    }
    return FormDefaultOptions;
}());
;
(function ($) {
    $.fn.form = function (options) {
        return new Form(this, options);
    };
})(jQuery);
//# sourceMappingURL=jquery.form.js.map