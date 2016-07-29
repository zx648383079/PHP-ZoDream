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
        Validate.passwordStrong = function (password) {
            if (password.length <= 4)
                return 0;
            var modes = 0;
            for (var i = 0; i < password.length; i++) {
                modes |= this.charMode(password.charCodeAt(i));
            }
            return this.bitTotal(modes);
        };
        Validate.charMode = function (iN) {
            if (iN >= 48 && iN <= 57)
                return 1;
            if (iN >= 65 && iN <= 90)
                return 2;
            if (iN >= 97 && iN <= 122)
                return 4;
            return 8;
        };
        Validate.bitTotal = function (num) {
            var modes = 0;
            for (var i = 0; i < 4; i++) {
                if (num & 1)
                    modes++;
                num >>>= 1;
            }
            return modes;
        };
        return Validate;
    }());
    exports.Validate = Validate;
});
//# sourceMappingURL=validate.js.map