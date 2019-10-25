interface IRange {
    start: number,
    end: number,
}

type PluginHandle = (this: Editor, parentBlock: string, event: JQuery) => void;

interface IPlugin {
    icon: string,
    name?: string,
    title?: string,
    handle: PluginHandle,
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
    private events: {[key: string]: Function} = {};

    public on(event: string, callback: Function): this {
        this.events[event] = callback;
        return this;
    }

    public hasEvent(event: string): boolean {
        return this.events.hasOwnProperty(event);
    }

    public trigger(event: string, ... args: any[]) {
        if (!this.hasEvent(event)) {
            return;
        }
        return this.events[event].call(this, ...args);
    }

    private init() {
        let html = '';
        for (const item of Editor.plugins) {
            html += '<i class="fa '+ item.icon +'" data-name="'+ item.name +'" title="' + item.title +'"></i>';
            this.on(item.name, item.handle);
        }
        this.element.prepend('<div class="editor-plugin">' + html + '</div>');
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
            that.trigger(plugin.data('name'), tag, plugin);
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
     * at
     */
    public at(parent: number, user: string) {
        let val = '<at parent="' + parent + '">'+ user +'</at>';
        this.textField.value = val + this.textField.value.replace(/<at[\s\S]+<\/at>/, '');
        $('html,body').animate({scrollTop: this.element.offset().top + 'px'}, 500);
        this.focus();
    }

    /**
     * clear
     */
    public clear(focus: boolean = true) {
        this.textField.value = '';
        if (!focus) {
            return;
        }
        this.focus();
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

    public static plugins: IPlugin[] = [];

    public static plugin(plugin: IPlugin| string, handle?: string | PluginHandle, title?: string | PluginHandle) {
        if (typeof plugin !== 'object') {
            plugin = {
                icon: plugin,
                title: (typeof handle === 'function' ? title : handle) as string, 
                handle: (typeof handle === 'function' ? handle : title) as PluginHandle, 
            };
        }
        if (!plugin.name) {
            plugin.name = plugin.icon;
        }
        if (!plugin.title) {
            plugin.title = plugin.name;
        }
        this.plugins.push(plugin);
    }
}

Editor.plugin('fa-code', '插入代码', function(tag: string) {
    if (tag && tag !== 'hide') {
        return;
    }
    this.insert('<code></code>', 6, true);
});
Editor.plugin('fa-eye-slash', '插入隐藏内容', function(tag) {
    if (tag) {
        return;
    }
    this.append('<hide></hide>', 6, true);
});
Editor.plugin('fa-download', '插入可下载内容', function(tag) {
    if (tag && tag !== 'hide') {
        return;
    }
    this.insert('<file></file>', 6, true)
});
Editor.plugin('fa-image', '插入图片', function(tag) {
    if (tag && tag !== 'hide' && tag !== 'a') {
        return;
    }
    this.insert('<img></img>', 5, true);
});
Editor.plugin('fa-music', '插入音频', function(tag) {
    if (tag && tag !== 'hide') {
        return;
    }
    this.insert('<audio></audio>', 7, true);
});
Editor.plugin('fa-video', '插入视频', function(tag) {
    if (tag && tag !== 'hide') {
        return;
    }
    this.insert('<video></video>', 7, true);
});
Editor.plugin('fa-link', '插入链接', function(tag) {
    if (tag && tag !== 'hide') {
        return;
    }
    this.insert('<a href=""></a>', 9, true);
});
Editor.plugin('fa-trash', '清空', function() {
    this.clear(true);
});
$(function() {
    let editor = new Editor($('.editor'));
    $('.thread-box').on('click', '.post-item [data-action="reply"]', function(e) {
        e.preventDefault();
        let box = $(this).closest('.post-item');
        editor.at(box.data('id'), box.find('.post-user .name').text());
    }).on('click', '[data-action="toggle"]', function(e) {
        e.preventDefault();
        let $this = $(this);
        if (!confirm('您确定要' + $this.text())) {
            return;
        }
        postJson($this.attr('href'), parseAjax);
    });
    $('.thread-list').on('click', '.thread-item a', function(e) {
        e.stopPropagation();
    }).on('click', '.thread-item', function() {
        window.location.href = $(this).find('a.title').attr('href');
    }).on('mouseover', '*[data-action=user]', function() {
        let $this = $(this);
    });
});