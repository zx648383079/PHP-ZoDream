define(["require", "exports"], function (require, exports) {
    "use strict";
    var Upload = (function () {
        function Upload() {
            this.mode = UploadMode.Single;
        }
        Upload.prototype.createElement = function () {
            this.element = document.createElement("input");
            this.element.type = "file";
            this.element.className = "_uploadFile";
            if (this.mode == UploadMode.Multiple) {
                this.element.multiple = true;
            }
            document.body.appendChild(this.element);
            if (this.changeCallback) {
                this.addEventListener(this.changeCallback);
            }
        };
        Upload.prototype.addEventListener = function (callback, event) {
            if (event === void 0) { event = 'change'; }
            if (!this.element) {
                this.createElement();
            }
            this.element.addEventListener(event, callback);
        };
        return Upload;
    }());
    exports.Upload = Upload;
    (function (UploadMode) {
        UploadMode[UploadMode["Single"] = 0] = "Single";
        UploadMode[UploadMode["Multiple"] = 1] = "Multiple";
    })(exports.UploadMode || (exports.UploadMode = {}));
    var UploadMode = exports.UploadMode;
});
//# sourceMappingURL=upload.js.map