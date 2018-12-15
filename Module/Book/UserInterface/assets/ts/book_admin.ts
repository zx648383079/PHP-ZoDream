const EMPTY_REGEX = /(^s*)|(s*$)/g;
function getStrLength(val: string): number {
    return val ? val.replace(EMPTY_REGEX, "").length : 0;
}
function bindChapter() {
    let lengthBox = $('.length-box span'),
        contentBox = $("#content-box"),
        showLength = function() {
            lengthBox.text(getStrLength(contentBox.val()));
        };
    contentBox.on('input propertychange', function() {
        showLength();
    });
    showLength();
}