window.exports = {};

function htmlFormat(html: string): string {
    return window.exports.beautifier.html(html, { indent_size: 4, space_in_empty_paren: true});
}

function formatJson(json, options?: any) {
    var reg = null,
            formatted = '',
            pad = 0,
            PADDING = '    ';
    options = options || {};
    options.newlineAfterColonIfBeforeBraceOrBracket = (options.newlineAfterColonIfBeforeBraceOrBracket === true) ? true : false;
    options.spaceAfterColon = (options.spaceAfterColon === false) ? false : true;
    if (typeof json !== 'string') {
        json = JSON.stringify(json);
    } else {
        json = JSON.parse(json);
        json = JSON.stringify(json);
    }
    reg = /([\{\}])/g;
    json = json.replace(reg, '\r\n$1\r\n');
    reg = /([\[\]])/g;
    json = json.replace(reg, '\r\n$1\r\n');
    reg = /(\,)/g;
    json = json.replace(reg, '$1\r\n');
    reg = /(\r\n\r\n)/g;
    json = json.replace(reg, '\r\n');
    reg = /\r\n\,/g;
    json = json.replace(reg, ',');
    if (!options.newlineAfterColonIfBeforeBraceOrBracket) {
        reg = /\:\r\n\{/g;
        json = json.replace(reg, ':{');
        reg = /\:\r\n\[/g;
        json = json.replace(reg, ':[');
    }
    if (options.spaceAfterColon) {
        reg = /\:/g;
        json = json.replace(reg, ':');
    }
    (json.split('\r\n')).forEach(function (node, index) {
        //console.log(node);
        var i = 0,
            indent = 0,
            padding = '';

        if (node.match(/\{$/) || node.match(/\[$/)) {
            indent = 1;
        } else if (node.match(/\}/) || node.match(/\]/)) {
            if (pad !== 0) {
                pad -= 1;
            }
        } else {
                indent = 0;
        }

        for (i = 0; i < pad; i++) {
            padding += PADDING;
        }

        formatted += padding + node + '\r\n';
        pad += indent;
    });
    return formatted;
}

function asciiEncode(content: string): string {
    let val = [];
    for (let i = 0; i < content.length; i++) {
        val.push(content.charAt(i)+":" + content.charCodeAt(i));
    }
    return val.join("\n");
}
function asciiDecode(content: string): string {
    return content.replace(/(\d+)/g, function(m, i) {
        return String.fromCharCode(parseInt(m));
    })
}

function unicodeDecode(content: string) {
    var div = document.createElement('div');
    div.innerHTML = content;
    return div.innerHTML;
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
        case 'ascii_encode':
            result = asciiEncode(content);
            break;
        case 'ascii_decode':
            result = asciiDecode(content);
            break;
        case 'unicode':
            result = escape(content).toLocaleLowerCase().replace(/%u/gi,'\\u');
            break;
        case 'deunicode':
            if (/&#\d+;/.test(content)) {
                result = unicodeDecode(content);
                break;
            }
            result = unescape(content.replace(/\\u/gi, '%u'));
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
        case 'jsonbeautify': 
            result = formatJson(content);
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
        let val = inputBox.val() + '';
        if (type == 'date' && val.length < 10) {
            inputBox.val(val = Math.floor(new Date().getTime() / 1000) + '');
        }
        outputBox.val(converter(val, type)).trigger('change');
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

enum BLOCK_TYPE {
    NONE,
    TAG,
    ATTR,
    ATTR_VALUE,
    END_TAG
}

function getAttr(html: string = '<br class="777"/>') {
    let node = {
        tag: undefined,
        attr: {}
    }, code: string, status: BLOCK_TYPE = BLOCK_TYPE.NONE, pos: number = 0, name: string, value: string, endTag: string,
        isTag = function(tag, start) {
            if (['br'].indexOf(tag) >= 0) {
                return true;
            }
            return html.indexOf('</' + tag + '>', start) > 0;
        },
        getTag = function() {
            let tag = '',
                po = pos;
            while (po < html.length) {
                code = html.charAt(po ++);
                if (code == ' ' || code == '/' || code == '>') {
                    return isTag(tag, po) ? tag : false;
                }
                tag += code;
            }
            return false;
        }
    while (pos < html.length) {
        code = html.charAt(pos ++);
        if (code == '<' && status == BLOCK_TYPE.NONE) {
            if (html.charAt(pos) == '/') {
                status = BLOCK_TYPE.END_TAG;
            }
            let tag = getTag();
            if (tag) {
                node.tag = tag;
                pos += tag.length;
                status = BLOCK_TYPE.TAG;
                continue;
            }
            continue;
        }
        if (code == '>' && (status == BLOCK_TYPE.TAG || status == BLOCK_TYPE.ATTR || status == BLOCK_TYPE.END_TAG)) {
            status = BLOCK_TYPE.NONE;
            continue;
        }
        if (code == '/' && status == BLOCK_TYPE.TAG) {
            if (html.charAt(pos) == '>') {
                status = BLOCK_TYPE.NONE;
                pos ++;
                continue;
            }
        }
        if (code == ' ' && status == BLOCK_TYPE.TAG) {
            status = BLOCK_TYPE.ATTR;
            name = '';
            value = '';
            continue;
        }
        if (code == ' ' && status == BLOCK_TYPE.ATTR) {
            status = BLOCK_TYPE.ATTR;
            node.attr[name] = true;
            name = '';
            continue;
        }
        if (!endTag && code == ' ' && status == BLOCK_TYPE.ATTR_VALUE) {
            status = BLOCK_TYPE.ATTR;
            node.attr[name] = value;
            name = '';
            value = '';
            continue;
        }
        if (!endTag && code == '/' && status == BLOCK_TYPE.ATTR_VALUE) {
            if (html.charAt(pos) == '>') {
                status = BLOCK_TYPE.NONE;
                node.attr[name] = value;
                name = '';
                value = '';
                pos ++;
                continue;
            }
        }
        if (code == '=' && status == BLOCK_TYPE.ATTR) {
            code = html.charAt(pos);
            status = BLOCK_TYPE.ATTR_VALUE;
            if (code == '\'' || code == '"') {
                endTag = code;
                pos ++;
                continue;
            }
            endTag = undefined;
            continue;
        }
        if (endTag && code == endTag && status == BLOCK_TYPE.ATTR_VALUE) {
            status = BLOCK_TYPE.TAG;
            node.attr[name] = value;
            name = '';
            value = '';
            continue;
        }
        if (status == BLOCK_TYPE.ATTR) {
            name += code;
            continue;
        }
        if (status == BLOCK_TYPE.ATTR_VALUE) {
            value += code;
        }
    }
    //console.log(node);
}