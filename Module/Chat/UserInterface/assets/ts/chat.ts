interface ChatMenuItem {
    name?: string,
    text?: string,
    icon?: string,
    toggle?: ()=>boolean,
    onclick?: (menuItem: ChatMenuItem) => void,
    children?: Array<ChatMenuItem>
}

class ChatMenu {
    /**
     *
     */
    constructor(
        private box: JQuery,
        private menus: Array<ChatMenuItem> = []
    ) {

    }

    private events: any = {};

    public addMenu(menu: ChatMenuItem | any) {
        this.menus.push(menu);
        return this;
    }

    public show(x: number, y: number) {
        this.box.css({
            'left': x + 'px',
            'top': y + 'px'
        }).show();
        return this;
    }

    public hide() {
        this.box.hide();
    }

    public refresh() {

    }

    private getMenuHtml(menus: Array<ChatMenuItem>) {
        let html = '';

    }

    private getMenuItemHtml(menu: ChatMenuItem): string {
        if (menu.toggle && menu.toggle() === false) {
            return null;
        }
        
    }

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
}

class ChatAddUserBox {
    /**
     *
     */
    constructor(
        public box: JQuery,
        private parent: ChatRoom
    ) {
        
    }

    public show() {
        this.box.show();
    }
}

class ChatUserInfoBox {
    /**
     *
     */
    constructor(
        public box: JQuery,
        private parent: ChatRoom
    ) {
        
    }

    public show() {
        this.box.show();
    }
}

class ChatSearchBox {
    /**
     *
     */
    constructor(
        public box: JQuery,
        private parent: ChatRoom
    ) {
        
    }

    /**
     * bindEvent
     */
    public bindEvent() {
        let _this = this;
        this.box.on('click', '.dialog-search-list .dialog-info', function() {
            _this.parent.addBox.show();
        });
        this.box.on('click', '.dialog-tab-header .dialog-tab-item', function() {
            let $this = $(this);
            $this.addClass('active').siblings().removeClass('active');
        });
    }

    public show() {
        this.box.show();
    }
}

class ChatEditor {
    constructor(
        public box: JQuery,
        private parent: ChatMessageBox
    ) {
        
    }

    public insertHtmlAtCaret(html: string) {
        let sel: Selection, range: Range, editor = this.box;
        if (!editor.is(":focus")) {
            editor.focus();
            range = document.createRange();
            range.setStartAfter(editor[0]);
            range.setEnd(editor[0], editor[0].childNodes.length);
    
        }
        if (!window.getSelection) {
            return;
        }
        sel = window.getSelection();
        if (!sel.getRangeAt || !sel.rangeCount) {
            return;
        }
        if (!range) {
            range = sel.getRangeAt(0);
            range.deleteContents();
        }
        let el = document.createElement("div");
        el.innerHTML = html;
        let frag = document.createDocumentFragment(), node, lastNode;
        while ( (node = el.firstChild) ) {
            lastNode = frag.appendChild(node);
        }
        range.insertNode(frag);
        if (lastNode) {
            range = range.cloneRange();
            range.setStartAfter(lastNode);
            range.collapse(true);
            sel.removeAllRanges();
            sel.addRange(range);
        }
    }
}

class ChatMessageBox {
    /**
     *
     */
    constructor(
        public box: JQuery,
        private parent: ChatRoom
    ) {
        
    }

    public editor: ChatEditor;

    public refresh() {
        this.editor = new ChatEditor(this.box.find('.dialog-message-text'), this);
    }

    /**
     * bindEvent
     */
    public bindEvent() {
        let _this = this;
        this.box.on('click', '.fa-smile-o', function() {
            _this.editor.insertHtmlAtCaret('<img src="./image/avatar.jpg" alt="">');
        });
    }
}

class ChatUserBox {
    /**
     *
     */
    constructor(
        public box: JQuery,
        private parent: ChatRoom
    ) {
        
    }

    public menu: ChatMenu;

    public refresh() {
        this.menu = new ChatMenu(this.box.find('.dialog-menu'));
    }

    public bindEvent() {
        let _this = this;
        $(document).click(function() {
            _this.menu.hide();
        }).on('selectionchange', function(e) {
            console.log(e);
        });
        this.box.click(function() {
            if ($(this).hasClass('dialog-min')) {
                $(this).removeClass('dialog-min');
            }
        });
        this.box.on('click', '.dialog-header .fa-minus', function(e) {
            e.stopPropagation();
            _this.box.addClass('dialog-min');
        });

        this.box.on('click', '.dialog-header .fa-plus', function() {
            _this.parent.searchBox.show();
        });
        this.box.on('click', '.dialog-tab .dialog-tab-header .dialog-tab-item', function() {
            let $this = $(this);
            $this.addClass('active').siblings().removeClass('active');
            $this.closest('.dialog-tab').find('.dialog-tab-box .dialog-tab-item').eq($this.index()).addClass('active').siblings().removeClass('active');
        });
        this.box.on('click', '.dialog-panel .dialog-panel-header', function() {
            $(this).closest('.dialog-panel').toggleClass('expanded');
        });
        this.box.on('click', '.dialog-tab .dialog-user', function() {
            $(this).closest('.dialog-chat').find('.dialog-chat-room').show();
        });
        this.box.on('contextmenu', '.dialog-tab .dialog-user', function(event) {
            _this.menu.show(event.clientX, event.clientY);
            return false;
        });
        this.menu.on('click', function() {
            _this.parent.userBox.show();
        });
    }
}

class ChatRoom {
    constructor(
        public target: JQuery
    ) {

    }

    public mainBox: ChatUserBox;
    public addBox: ChatAddUserBox;
    public userBox: ChatUserInfoBox;
    public searchBox: ChatSearchBox;
    public chatBox: ChatMessageBox;

    public refresh() {
        this.mainBox = new ChatUserBox(this.target.find('.dialog-chat-box'), this);
        this.addBox = new ChatAddUserBox(this.target.find('.dialog-add-box'), this);
        this.userBox = new ChatUserInfoBox(this.target.find('.dialog-user-box'), this);
        this.searchBox = new ChatSearchBox(this.target.find('.dialog-search-box'), this);
        this.chatBox = new ChatMessageBox(this.target.find('.dialog-chat-room'), this);
    }
}

new ChatRoom($('.dialog-chat-page'));



$(function() {
    $('.dialog-box').on('click', '.dialog-header .fa-close', function() {
        $(this).closest('.dialog-box').hide();
    });
});