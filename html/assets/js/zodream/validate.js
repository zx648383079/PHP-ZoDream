define(["require", "exports"], function (require, exports) {
    "use strict";
    var Validate = (function () {
        function Validate() {
        }
        Validate.email = function (arg) {
            return this.isMatch('^\S+@\S+\.[\w]+', arg);
        };
        Validate.mobile = function (arg) {
            return this.isMatch('^13[\d]{9}$|^14[57]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0678]{1}\d{8}$|^18[\d]{9}$', arg + '');
        };
        Validate.telephone = function (arg) {
            return this.isMatch('^((\+?86)|(\(\+86\)))?\d{3,4}-\d{7,8}(-\d{3,4})?$', arg);
        };
        Validate.length = function (arg, min, max) {
            return arg.length >= min && arg.length <= max;
        };
        Validate.size = function (arg, min, max) {
            return arg >= min && arg <= max;
        };
        Validate.url = function (arg) {
            return this.isMatch('^((https?|ftp)?(://))?\S+\.\S+(/.*)?$', arg);
        };
        Validate.isMatch = function (pattern, arg) {
            var regex = new RegExp(pattern);
            return regex.test(arg);
        };
        return Validate;
    }());
    exports.Validate = Validate;
});
//# sourceMappingURL=validate.js.map