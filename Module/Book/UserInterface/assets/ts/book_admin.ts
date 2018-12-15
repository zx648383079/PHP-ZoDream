const OTHER_CODE = [8220, 8221, 8216, 8217, 65281, 12290, 65292, 12304, 12305, 12289, 65311, 65288, 65289, 12288];
function getStrLength(val: string): number {
    if (!val) {
        return 0;
    }
    let i = 0, code: number, length = 0;
    while (code = val.charCodeAt(i)) {
        i ++;
        if (code < 48 
            || (code > 57 && code < 65)
            || (code > 90 && code < 97)
            || (code > 122 && code < 128)
            || (code > 128 && OTHER_CODE.indexOf(code) >= 0)
            ) {
            continue;
        }
        length ++;
    }
    return length;
}
function bindChapter() {
    let lengthBox = $('.length-box'),
        contentBox = $("#content-box"),
        showLength = function() {
            let length = getStrLength(contentBox.val());
            lengthBox.find('span').text(length);
            lengthBox.find('input').val(length);
        };
    contentBox.on('input propertychange', function() {
        showLength();
    }).on('keydown', function(e) {
        if (e.keyCode === 9) {
            e.preventDefault();
            let position = this.selectionStart + 4;
            this.value = this.value.substr(0, this.selectionStart)+'    ' + this.value.substr(this.selectionStart);
            this.selectionStart = position;
            this.selectionEnd = position;
            this.focus();
        }
    });
    showLength();
}