define(["require", "exports"], function (require, exports) {
    "use strict";
    var Carousel = (function () {
        function Carousel(element, options) {
            this.element = element;
            this.options = $.extend({}, CarouselDefaultOptions, options);
            var items = this.element.find(options.itemTag);
            this._itemWidth = items.width();
            this._itemLength = items.length;
            if (!options.range) {
                options.range = this._itemWidth;
            }
            this._box = this.element.find(options.boxTag);
            this._width = items.width() * this._itemLength;
        }
        Object.defineProperty(Carousel.prototype, "left", {
            get: function () {
                return this._left;
            },
            set: function (left) {
                if (left > 0) {
                    left = 0;
                }
                else if (left < -this._width) {
                    left = -this._width;
                }
                if (left == this._left) {
                    return;
                }
                this._left = left;
                this._box.animate({ left: this._left + "px" }, this.options.animationTime, this.options.animationMode);
            },
            enumerable: true,
            configurable: true
        });
        Carousel.prototype.next = function (range) {
            if (range === void 0) { range = this.options.range; }
            this.left = Math.max(-this._width, this._left - range);
        };
        Carousel.prototype.previous = function (range) {
            if (range === void 0) { range = this.options.range; }
            this.left = Math.min(0, this._left + range);
        };
        Carousel.prototype.goto = function (index) {
            this.left = -index * this._itemWidth;
        };
        return Carousel;
    }());
    exports.Carousel = Carousel;
    var CarouselDefaultOptions = (function () {
        function CarouselDefaultOptions() {
            this.itemTag = 'li';
            this.boxTag = '.box';
            this.animationTime = '1s';
            this.animationMode = 'slow';
        }
        return CarouselDefaultOptions;
    }());
});
//# sourceMappingURL=carousel.js.map