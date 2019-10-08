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

    }

    private bindEvent() {
        let that = this;
        this.element.on('keydown', 'textarea', function(e) {
            if (e.keyCode === 9) {
                e.preventDefault();
                that.insert('    ', 4, true);
            }
        }).on('blur', 'textarea', function(e) {
            that.range = {
                start: this.selectionStart,
                end: this.selectionEnd
            };
        }).on('click', '.editor-plugin .fa', function() {
            let plugin = $(this);
            if (plugin.hasClass('fa-code')) {
                that.insert('<code></code>', 6, true);
                return;
            }
            if (plugin.hasClass('fa-eye-slash')) {
                that.insert('<hide></hide>', 6, true);
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

    public insert(val: string, move: number = 0, focus: boolean = true) {
        this.checkRange();
        this.textField.value = this.textField.value.substr(0, this.range.start)+ val + this.textField.value.substr(this.range.start);
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
            if (move > str.length) {
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