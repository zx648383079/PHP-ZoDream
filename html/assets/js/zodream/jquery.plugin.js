define(["require", "exports", './carousel'], function (require, exports, carousel_1) {
    "use strict";
    ;
    (function ($) {
        $.fn.Carousel = function (options) {
            return new carousel_1.Carousel(this, options);
        };
    })(jQuery);
});
//# sourceMappingURL=jquery.plugin.js.map