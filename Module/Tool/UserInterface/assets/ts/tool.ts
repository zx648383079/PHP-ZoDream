function converter(content: string, type: string): string| number {
    let result: string| number = '';
    switch (type) {
        case 'urlencode':
            result = encodeURIComponent(content);
            break;
        case 'urldecode':
            result = decodeURIComponent(content);
            break;
        case 'unicode':
            result = escape(content).toLocaleLowerCase().replace(/%u/gi,'\\u');
            break;
        case 'deunicode':
            result = unescape(content.replace(/\\u/gi,'%u'));
            break;
        case 'strtotime': 
            result = new Date(content).getTime();
            break;
        case 'htmltowxml': 
            result = this.HTMLtoWXML(content);
            break;
        case 'htmlbeautify':
            result = html_beautify(content);
            break;
        case 'jsbeautify': 
            result = js_beautify(content);
            break;
        case 'cssbeautify': 
            result = css_beautify(content);
            break;
        case 'date': 
            result = new Date(parseInt(content) * 1000).toLocaleString();
            break;
        default:
            break;
    }
    return result;
}

const ASYNC_TYPE = ['md5', 'password_hash', 'sha1', 'base64_encode', 'base64_decode'];

$(function() {
    $(document).on('click', '.converter-box .actions button', function() {
        let _this = $(this), 
            box = _this.closest('.converter-box'),
            inputBox = box.find('.input-box textarea'),
            outputBox = box.find('.output-box textarea'),
            type = _this.data('type');
        if (!type) {
            type = box.find('.actions select').val();
        }
        if (type == 'clear') {
            inputBox.val('');
            outputBox.val('');
            return;
        }
        if (ASYNC_TYPE.indexOf(type) >= 0) {
            postJson('../converter', {
                content: inputBox.val(),
                type: type
            }, function(data) {
                if (data.code != 200) {
                    return;
                }
                outputBox.val(data.data.result);
            });
        }
        if (type == 'date') {
            inputBox.val(Math.floor(new Date().getTime() / 1000));
        }
        outputBox.val(converter(inputBox.val() + '', type));
    });
});