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
    return strMap(content, (str, code) => {
        return str + ':' + code;
    }, "\n");
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

function strMap(content: string, cb: (str: string, code: number) => string|false|void, link = ''): string {
    if (!content || content.length < 1) {
        return;
    }
    let items = [];
    for (let i = 0; i < content.length; i++) {
        const val = cb(content.charAt(i), content.charCodeAt(i));
        if (typeof val === 'boolean' && !val) {
            break;
        }
        if (typeof val === 'string') {
            items.push(val);
        }
    }
    return items.join(link);
}

function matchReplace(content: string, reg: RegExp, cb: (matches: string[]) => string): string {
    if (!content || content.length < 1) {
        return;
    }
    let res: RegExpExecArray;
    let str = content;
    while ((res = reg.exec(content)) != null)  {
        str = str.replace(res[0], cb(res));
    }
    return str;
}

function isInt(content: string): boolean {
    return /^\d+$/.test(content);
}

class Color {
    constructor(
        public r?: number,
        public g?: number, 
        public b?: number, 
        public a: number = 1) {
    }

    public hex(num: number): string {
        if (num > 255) {
            num = 255
        }
        return ('0' + num.toString(16)).slice(-2);
    }

    public toHex(): string {
        return '#' + this.hex(this.r) + this.hex(this.g) + this.hex(this.b);
    }

    public toRGB() {
        return 'rgb(' + this.r + ',' + this.g + ',' + this.b + ')';
    }

    public toRGBA() {
        let a = this.a.toString();
        if (a.length > 5) {
            a = this.a.toFixed(3);
        }
        return 'rgba(' + this.r + ',' + this.g + ',' + this.b + ',' + a + ')';
    }

    public toARGB() {
        return '#' + this.hex(Math.round(this.a * 256 - 1)) + this.hex(this.r) + this.hex(this.g) + this.hex(this.b);
    }

    public toHSL() {
        let r = this.r / 255, g = this.g / 255, b = this.b / 255;
        let max = Math.max(r, g, b), min = Math.min(r, g, b);
        let h: number, s: number, l = (max + min) / 2;
        if (max === min) {
            h = s = 0;
        } else {
            let d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r:
                    h = (g - b) / d + (g < b ? 6 : 0);
                    break;
                case g:
                    h = (b - r) / d + 2;
                    break;
                case b:
                    h = (r - g) / d + 4;
                    break;
            }
            h /= 6;
        }
        return 'hsl(' + Math.round(h * 360) + ',' + Math.round(s * 100) + "%" + ',' + Math.round(l * 100) + "%" + ')';
    }

    public static from(content: string): Color {
        const first = content.charAt(0);
        if (first === '#') {
            if (content.length === 8) {
                return Color.fromARGB(content);
            }
            return Color.fromHex(content);
        }
        if (first == 'h') {
            return Color.fromHSL(content);
        }
        return Color.fromRGBA(content);
    }

    public static fromHSL(val: string): Color {
        let hsl = /hsl\((\d+),\s*([\d.]+)%,\s*([\d.]+)%\)/g.exec(val);
        let h = parseInt(hsl[1]) / 360, s = parseInt(hsl[2]) / 100, l = parseInt(hsl[3]) / 100;
        if (s === 0) {
            return new Color(Math.round(l * 255), Math.round(l * 255), Math.round(l * 255));
        }
        let hue2rgb = function hue2rgb(p: number, q: number, t: number) {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1 / 6) return p + (q - p) * 6 * t;
            if (t < 1 / 2) return q;
            if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
            return p;
        };
        const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        const p = 2 * l - q;
        const r = hue2rgb(p, q, h + 1 / 3);
        const g = hue2rgb(p, q, h);
        const b = hue2rgb(p, q, h - 1 / 3);
        return new Color(Math.round(r * 255), Math.round(g * 255), Math.round(b * 255));
    }

    public static fromRGBA(val: string): Color {
        let rgba = /^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/.exec(val);
        return new Color(parseInt(rgba[1]), parseInt(rgba[2]), parseInt(rgba[3]), parseFloat(rgba[4] || '1'));
    }

    public static fromARGB(val: string): Color {
        let argb = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(val);
        return new Color(parseInt(argb[2], 16), parseInt(argb[3], 16), parseInt(argb[4], 16), parseInt(argb[1], 16) / 255);
    }

    public static fromHex(val: string): Color {
        let rgx = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        let hex = val.replace(rgx, function (_, r: string, g: string, b: string) {
            return r + r + g + g + b + b;
        });
        let rgb = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return new Color(parseInt(rgb[1], 16), parseInt(rgb[2], 16), parseInt(rgb[3], 16));
    }
}

function colorConverter(content: string): string {
    const colorDisplay = $('.converter-box');
    if (content.length < 1) {
        colorDisplay.removeAttr('style');
        return '';
    }
    const color = Color.from(content.trim().toLowerCase());
    colorDisplay.css('background-color', color.toRGBA());
    return [
        'HEX: ' + color.toHex(),
        'RGB: ' + color.toRGB(),
        'RGBA: ' + color.toRGBA(),
        'ARGB: ' + color.toARGB(),
        'HSL: ' + color.toHSL(),
    ].join('\n');
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
        case 'color_converter':
            result = colorConverter(content.trim());
            break;
        case 'ascii_decode':
            result = asciiDecode(content);
            break;
        case 'binaryencode':
            if (isInt(content)) {
                result = parseInt(content, 10).toString(2);
            } else {
                result = strMap(content, (_, code) => {
                    return '\\b' + code.toString(2);
                });
            }
            break;
        case 'binarydecode':
            if (/^[01]+$/.test(content)) {
                result = parseInt(content, 2);
            } else {
                result = matchReplace(content, /\\b([01]+)/.test(content) ? /\\b([01]+)/g : /0b([01]+)/g, matches => {
                    return String.fromCharCode(parseInt(matches[1], 2));
                });
            }
            break;
        case 'octalencode':
            if (isInt(content)) {
                result = parseInt(content, 10).toString(8);
            } else {
                result = strMap(content, (_, code) => {
                    return '\\' + code.toString(8);
                });
            }
            break;
        case 'octaldecode':
            if (/^[0-7]+$/.test(content)) {
                result = parseInt(content, 8);
            } else {
                result = matchReplace(content, /\\([0-7]+)/.test(content) ? /\\([0-7]+)/g : /0([0-7]+)/g, matches => {
                    return String.fromCharCode(parseInt(matches[1], 8));
                });
            }
            break;
        case 'hexencode':
            if (isInt(content)) {
                result = parseInt(content, 10).toString(16);
            } else {
                result = strMap(content, (_, code) => {
                    return '\\x' + code.toString(16);
                });
            }
            break;
        case 'hexdecode':
            if (/^[0-9a-f]+$/i.test(content)) {
                result = parseInt(content, 16);
            } else {
                result = matchReplace(content, /\\x([0-9a-f]+)/i.test(content) ? /\\x([0-9a-f]+)/ig : /0x([0-9a-f]+)/ig, matches => {
                    return String.fromCharCode(parseInt(matches[1], 16));
                });
            }
            
            break;
        case 'unicode':
            result = encodeURIComponent(content).toLocaleLowerCase().replace(/%u/gi,'\\u');
            break;
        case 'deunicode':
            if (/&#\d+;/.test(content)) {
                result = unicodeDecode(content);
                break;
            }
            result = decodeURIComponent(content.replace(/\\u/gi, '%u'));
            break;
        case 'strtotime': 
            result = new Date(content).getTime();
            break;
        case 'htmltowxml': 
            result = htmlFormat(this.HTMLtoWXML(content));
            break;
        case 'csstoscss': 
            result = this.cssToScss(content);
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
    $('#output').on('change', function() {
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