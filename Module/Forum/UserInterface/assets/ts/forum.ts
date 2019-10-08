interface IRange {
    start: number,
    end: number,
}

class Editor {
    constructor(
        public element: JQuery
    ) {
        this.textField = this.element.find('textarea')[0] as HTMLTextAreaElement;
        this.init();
        this.bindEvent();
    }

    public range: IRange;
    public textField: HTMLTextAreaElement;

    private init() {
        this.element.prepend(`<div class="editor-plugin">
        <i class="fa fa-code" title="插入代码"></i>
        <i class="fa fa-eye-slash" title="插入隐藏内容""></i>
        <i class="fa fa-download" title="插入可下载内容"></i>
        <i class="fa fa-image" title="插入图片"></i>
        <i class="fa fa-video" title="插入视频"></i>
        <i class="fa fa-link" title="插入链接"></i>
    </div>`);
    }

    private bindEvent() {
        let that = this;
        this.element.on('keydown', 'textarea', function(e) {
            if (e.keyCode === 9) {
                e.preventDefault();
                that.saveRange();
                that.insertTab();
            }
        }).on('blur', 'textarea', function(e) {
            that.saveRange();
        }).on('click', '.editor-plugin .fa', function() {
            let plugin = $(this);
            if (that.isInAttr()) {
                return;
            }
            let tag = that.getBlock();
            if (tag && tag !== 'hide' && (tag !== 'a' || !plugin.hasClass('fa-image'))) {
                return;
            }
            if (plugin.hasClass('fa-code')) {
                that.insert('<code></code>', 6, true);
                return;
            }
            if (plugin.hasClass('fa-eye-slash')) {
                that.append('<hide></hide>', 6, true);
                return;
            }
            if (plugin.hasClass('fa-download')) {
                that.insert('<file></file>', 6, true);
                return;
            }
            if (plugin.hasClass('fa-image')) {
                that.insert('<img></img>', 5, true);
                return;
            }
            if (plugin.hasClass('fa-video')) {
                that.insert('<video></video>', 7, true);
                return;
            }
            if (plugin.hasClass('fa-link')) {
                that.insert('<a href=""></a>', 9, true);
                return;
            }
        });
    }

    /**
     * checkRange
     */
    public checkRange() {
        if (!this.range) {
            this.range = {
                start: this.textField.value.length,
                end: this.textField.value.length
            };
        }
    }

    /**
     * name
     */
    public saveRange() {
        this.range = {
            start: this.textField.selectionStart,
            end: this.textField.selectionEnd
        }
    }

    /**
     * isInAttr
     */
    public isInAttr() {
        if (!this.range || this.range.start < 3) {
            return false;
        }
        if (!/\<[^\<\>]+$/.test(this.textField.value.substr(0, this.range.start))) {
            return false;
        }
        if (this.range.start < this.range.end && /[\<\>]/.test(this.textField.value.substr(this.range.start, this.range.end - this.range.start))) {
            return false;
        }
        if (!/^[^\<\>]*\>/.test(this.textField.value.substr(this.range.end))) {
            return false;
        }
        return true;
    }

    /**
     * getBlock
     */
    public getBlock(): string {
        if (!this.range) {
            return null;
        }
        let val = this.textField.value;
        if (val.length < 3) {
            return null;
        }
        let x = this.range.start;
        if (x < 2) {
            return null;
        }
        let end = 0;
        let status = 0;
        while (x > 0) {
            x --;
            let code = val.charAt(x);
            if (status === 0 && code === '>') {
                status = 1;
                continue;
            }
            if (status === 1 && code === '/') {
                status = 2;
                continue;
            }
            if (status === 2) {
                if (code !== '<') {
                    status = 1;
                    continue;
                }
                status = 0;
                end ++;
                continue;
            }
            if (status === 1 && code == '<') {
                if (end > 0) {
                    end --;
                    continue;
                }
                return this.getBlockTag(x + 1, val);
            }
        }
        return null;
    }

    private getBlockTag(x: number, val: string) {
        let tag = [];
        while (x < val.length) {
            let code = val.charAt(x);
            if (code === ' ' || code === '>') {
                break;
            }
            tag.push(code);
            x ++;
        }
        return tag.join('');
    }

    /**
     * insertTab
     */
    public insertTab() {
        if (!this.isInAttr()) {
            return this.insert('    ', 4, true);
        }
        let x = this.range.start;
        let val = this.textField.value;
        while (true) {
            let code = val.charAt(x);
            if (code === '>') {
                break;
            }
            x ++;
        }
        this.range.start = x + 1;
        this.range.end = Math.max(x + 1, this.range.end)
        this.focus();
    }

    public insert(val: string, move: number = 0, focus: boolean = true) {
        this.checkRange();
        this.textField.value = this.textField.value.substr(0, this.range.start) + val + this.textField.value.substr(this.range.start);
        this.move(move);
        if (!focus) {
            return;
        }
        this.focus();
    }

    /**
     * replace
     */
    public replace(val: (str: string) => string | string, move: number = 0, focus: boolean = true) {
        this.checkRange();
        if (this.range.start === this.range.end) {
            return this.insert(typeof val === 'function' ? val('') : val, move, focus);
        }
        const str = typeof val === 'function' ? val(this.textField.value.substr(this.range.start, this.range.end - this.range.start)) : val;
        this.textField.value = this.textField.value.substr(0, this.range.start)+ str + this.textField.value.substr(this.range.end);
        this.move(move);
        if (!focus) {
            return;
        }
        this.focus();
    }

    public append(val: string, move: number = 0, focus: boolean = true) {
        this.replace(str => {
            if (str.length < 1) {
                return val;
            }
            if (move < 1) {
                return str + val;
            }
            if (move > val.length) {
                return val + str;
            }
            return val.substr(0, move) + str + val.substr(move);
        }, move, focus);
    }

    /**
     * move
     */
    public move(x: number) {
        if (x === 0) {
            return;
        }
        x = this.range.start + x;
        this.range = {
            start: x,
            end: Math.max(x, this.range.end)
        };
    }

    /**
     * focus
     */
    public focus() {
        this.checkRange();
        this.textField.selectionStart = this.range.start;
        this.textField.selectionEnd = this.range.end;
        this.textField.focus();
    }


}

$(function() {
    let editor = new Editor($('.editor'));
});