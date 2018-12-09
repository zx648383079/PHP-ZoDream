window.exports = {};

function htmlFormat(html: string): string {
    return window.exports.beautifier.html(html, { indent_size: 4, space_in_empty_paren: true});
}

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
            result = htmlFormat(this.HTMLtoWXML(content));
            break;
        case 'htmlbeautify':
            result = htmlFormat(content);
            break;
        case 'jsbeautify': 
            result = window.exports.beautifier.js(content, { indent_size: 4, space_in_empty_paren: true});
            break;
        case 'cssbeautify': 
            result = window.exports.beautifier.css(content, { indent_size: 4, space_in_empty_paren: true});
            break;
        case 'date': 
            result = new Date(parseInt(content) * 1000).toLocaleString();
            break;
        default:
            break;
    }
    return result;
}

function createScript(src){
    var $s = document.createElement('script');
    $s.setAttribute('src',src);
    return $s;
}

const ASYNC_TYPE = ['md5', 'password_hash', 'sha1', 'base64_encode', 'base64_decode'];

$(function() {
    document.body.appendChild(createScript('https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.8.10-beta1/beautifier.min.js'));

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
        outputBox.val(converter(inputBox.val() + '', type)).trigger('change');
    });
});

function registerEditor(mode: string) {
    let option = {
        lineNumbers: true,
        lineWrapping: true,    // 自动换行
        indentUnit: 4,         // 缩进单位为4
        styleActiveLine: true, // 当前行背景高亮
        matchBrackets: true,   // 括号匹配
        mode: mode
      },
    editor = CodeMirror.fromTextArea(document.getElementById("input"), option), 
    editor2 = CodeMirror.fromTextArea(document.getElementById("output"), option);
    editor.on('change', function() {
        editor.save();
    });
    $('#output').change(function() {
        editor2.setValue($(this).val());
    });
}