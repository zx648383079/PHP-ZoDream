define(["require", "exports"], function (require, exports) {
    "use strict";
    var Page = (function () {
        function Page(count, size) {
            if (size === void 0) { size = 8; }
            this.count = count;
            this.size = size;
            this.template = '<nav><ul class="pagination pagination-sm">{content}</ul></nav>';
            this.activeTemplate = '<li class="active"><a href="#">{content}<span class="sr-only">(current)</span></a></li>';
            this.pageTemplate = '<li><a href="{page}">{content}</a></li>';
            this.firstTemplate = '';
            this.lastTemplate = '';
            this.nextTemplate = '<li><a href="{page}" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a></li>';
            this.previousTemplate = '<li> <a href="{content}" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a> </li>';
            this.index = 1;
        }
        Page.prototype._fist = function () {
            if (this.index < 2) {
                return null;
            }
            return this.replace(1, 'First', this.firstTemplate);
        };
        Page.prototype._last = function () {
            if (this.index >= this.count) {
                return null;
            }
            return this.replace(this.count, 'Last', this.lastTemplate);
        };
        Page.prototype._previous = function () {
            if (this.index < 2) {
                return;
            }
            return this.replace(this.index - 1, 'Previous', this.previousTemplate);
        };
        Page.prototype._next = function () {
            if (this.index > this.count - 1) {
                return null;
            }
            return this.replace(this.index + 1, 'Next', this.nextTemplate);
        };
        Page.prototype._pageList = function () {
            if (this.count < 2) {
                return;
            }
            var linkPage = this.replace(1);
            var lastList = Math.floor(this.count / 2);
            var i = 0;
            var length = 0;
            if (this.size < this.count || this.index - lastList < 2 || this.size - this.count < 2) {
                i = 2;
                if (this.size <= this.count) {
                    length = this.size - 1;
                }
                else {
                    length = this.count;
                }
            }
            else if (this.index - lastList >= 2 && this.index + lastList <= this.size) {
                i = this.index - lastList;
                length = this.index + lastList - 1;
            }
            else if (this.index + lastList > this.size) {
                i = this.size - this.count + 1;
                length = this.size - 1;
            }
            if (this.index > lastList + 1 && i > 2) {
                linkPage += this.replace(null, '...', this.activeTemplate);
            }
            for (; i <= length; i++) {
                linkPage += this.replace(i);
            }
            if (this.index < this.size - lastList && length < this.size - 1) {
                linkPage += this.replace(null, '...', this.activeTemplate);
            }
            linkPage += this.replace(this.size);
            return linkPage;
        };
        Page.prototype.replace = function (page, content, template) {
            if (page === void 0) { page = 1; }
            if (content === void 0) { content = 0; }
            if (template === void 0) { template = this.pageTemplate; }
            if (!template) {
                return null;
            }
            if (content == 0) {
                content = page;
            }
            return template.replace("{page}", page + '').replace('content', content + '');
        };
        Page.prototype.create = function () {
            return this.template.replace('{content}', this._fist() + this._previous() + this._pageList() + this._next() + this._last());
        };
        return Page;
    }());
    exports.Page = Page;
});
//# sourceMappingURL=page.js.map