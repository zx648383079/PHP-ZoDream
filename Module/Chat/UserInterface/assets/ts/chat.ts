interface IMenu {
    name?: string,
    text?: string,
    icon?: string,
    toggle?: (target: JQuery, menu: ChatMenu)=>boolean,
    onclick?: (menuItem: JQuery, target: JQuery, menu: ChatMenu) => void| boolean,
    children?: Array<IMenu>
}

interface IFriendGroup<T> {
    id?: number,
    name: string,
    count?: number,
    online_count?: number,
    users: Array<T>
}

interface IFriend {
    id: number,
    name: string,
    user: IUser,
    new_count?: number,
    last_message?: IMessage
}

interface IGroup {
    id: number,
    name: string,
    cover: string,
    brief: string,
}

interface IUser {
    id: number,
    name: string,
    avatar: string,
    new_count: number,
    brief: string,
}

interface IApplyLog {
    id?: number,
    remark?: string,
    user: IUser,
    applier?: IUser
}

enum ChatType {
    MESSAGE,
    MORE,
    TIP,
    TIME
}

interface IMessage {
    type: ChatType
    content?: string,
    time?: number,
    user?: IUser
}

const EVENT_REFRESH_USERS = 'refresh_users',
    EVENT_REFRESH_GROUPS = 'refresh_groups',
    EVENT_GROUP_USERS = 'refresh_group_users',
    EVENT_USER_GROUPS = 'user_groups',
    EVENT_USER_INFO = 'user_info',
    EVENT_GROUP_INFO = 'group_info',
    EVENT_SEARCH_USERS = 'search_users',
    EVENT_SEARCH_GROUPS = 'search_groups',
    EVENT_APPLY_USERS = 'apply_users',
    EVENT_ADD_USER = 'add_user',
    EVENT_APPLY_USER = 'apply_user',
    EVENT_SEND_MESSAGE = 'send_message',
    EVENT_GET_MESSAGE = 'get_message',
    HTML_MAIN = `<div class="dialog-box dialog-chat-box">
    <div class="dialog-header">
        <div class="dialog-action">
            <i class="fa fa-plus"></i>
            <i class="fa fa-minus"></i>
            <i class="fa fa-close"></i>
        </div>
    </div>
    <div class="dialog-info">
        <div class="dialog-info-avatar">
            <img src="" alt="">
        </div>
        <div class="dialog-info-name">
            <h3></h3>
            <p>......</p>
        </div>
        <div class="dialog-message-count">
            99
        </div>
    </div>
    <div class="dialog-tab">
        <div class="dialog-tab-header">
            <div class="dialog-tab-item active">
                <i class="fa fa-comment"></i>
            </div><div class="dialog-tab-item">
                <i class="fa fa-user"></i>
            </div><div class="dialog-tab-item">
                <i class="fa fa-comments"></i>
            </div>
        </div>
        <div class="dialog-tab-box">
            <div class="dialog-tab-item active">
            </div>
            <div class="dialog-tab-item">
            </div>
            <div class="dialog-tab-item">
            </div>
        </div>
    </div>
    <div class="dialog-menu">
        <ul>
            <li>
                <i class="fa fa-eye"></i>
                查看资料</li>
            <li>
                <i class="fa fa-bookmark"></i>
                移动好友</li>
            <li>
                <i class="fa fa-trash"></i>
                删除好友</li>
        </ul>
    </div>
</div>`,
    HTML_ROOM = `<div class="dialog-box dialog-chat-room">
    <div class="dialog-header">
        <div class="dialog-title">与 xx 聊天中</div>
        <div class="dialog-action">
            <i class="fa fa-minus"></i>
            <i class="fa fa-close"></i>
        </div>
    </div>
    <div class="dialog-message-box">
        
    </div>
    <div class="dialog-message-tools">
        <i class="fa fa-smile"></i>
        <i class="fa fa-image"></i>
        <i class="fa fa-camera"></i>
        <i class="fa fa-video"></i>
        <i class="fa fa-file"></i>
        <i class="fa fa-gift"></i>
    </div>
    <div class="dialog-message-editor">
        <div class="dialog-message-text" contenteditable="true">

        </div>
        <div class="dailog-message-action">
            <button>发送</button>
        </div>
    </div>
</div>`,
    HTML_SEARCH_USER = `<div class="dialog-box dialog-search-box">
    <div class="dialog-header">
        <div class="dialog-title dialog-tab-header">
            <div class="dialog-tab-item active">找人</div>
            <div class="dialog-tab-item">找群</div>
        </div>
        <div class="dialog-action">
            <i class="fa fa-close"></i>
        </div>
    </div>
    <div class="dialog-search">
        <input type="text">
        <i class="fa fa-search"></i>
    </div>
    <div class="dialog-search-list">
    </div>
</div>`,
    HTML_USER_INFO = `<div class="dialog-box dialog-user-box">
    <div class="dialog-header">
        <div class="dialog-action">
            <i class="fa fa-close"></i>
        </div>
    </div>
    <div class="dialog-user-avatar">
        <img src="./image/avatar.jpg" alt="">
    </div>
    <h3 class="user-name">1231</h3>
    <div class="dialog-user-info">
        <p class="user-brief">123123</p>
        <p>123123</p>
        <p>123123</p>
    </div>
</div>`,
    HTML_APPLY = `<div class="dialog-box dialog-apply-box">
    <div class="dialog-header">
        <div class="dialog-action">
            <i class="fa fa-close"></i>
        </div>
    </div>
    <div class="dialog-user-avatar">
        <img src="./image/avatar.jpg" alt="">
    </div>
    <h3 class="user-name">1231</h3>
    <div class="dialog-add-action">
        <textarea name="" placeholder="我是123123"></textarea>
        <select name="" id="">
            <option value="">选择分组</option>
        </select>
        <button class="dialog-yes">申请</button>
    </div>
</div>`,
    HTML_AGREE_APPLY = `<div class="dialog-box dialog-add-box">
    <div class="dialog-header">
        <div class="dialog-action">
            <i class="fa fa-close"></i>
        </div>
    </div>
    <div class="dialog-user-avatar">
        <img src="./image/avatar.jpg" alt="">
    </div>
    <h3 class="user-name">1231</h3>
    <p class="user-brief">留言</p>
    <div class="dialog-add-action">
        <select name="" id="">
            <option value="">选择分组</option>
        </select>
        <button class="dialog-yes">同意</button>
        <button>拒绝</button>
    </div>
</div>`,
    HTML_APPLY_LOG = `<div class="dialog-box dialog-apply-log-box">
    <div class="dialog-header">
        <div class="dialog-title">验证消息</div>
        <div class="dialog-action">
            <i class="fa fa-close"></i>
        </div>
    </div>
    <div class="dialog-apply-list">
        <div class="dialog-info" data-id="2">
            <div class="dialog-info-avatar">
                <img src="http://zodream.localhost/assets/images/avatar/18.png" alt="">
            </div>
            <div class="dialog-info-name">
                <h3>1606282309</h3>
                <p>附加消息：123</p>
            </div>
            <div class="dialog-action">
                <button>同意</button>
                <button>忽略</button>
            </div>
        </div>
    </div>
</div>`;

abstract class ChatBaseBox {
    private cache_element: any = {};
    public box: JQuery;

    public find(tag: string): JQuery {
        if (this.cache_element.hasOwnProperty(tag)) {
            return this.cache_element[tag];
        }
        return this.cache_element[tag] = this.box.find(tag);
    }

    public show() {
        this.box.show();
        return this;
    }

    /**
     * center
     */
    public center() {
        this.box.css({
            left: Math.max(0, ($(window).width() - this.box.outerWidth())/ 2) + "px", 
            top: Math.max(0, ($(window).height() - this.box.outerHeight())/ 2) + "px"
        });
        return this;
    }

    public hide() {
        this.box.hide();
    }

    public static format(arg: string, ...args: any[]) {
        return arg.replace(/\{(\d+)\}/g, function(m,i) {
            return args[i];
        });
    }
}

class ChatMenu extends ChatBaseBox {
    /**
     *
     */
    constructor(
        public box: JQuery,
        menus: IMenu[]
    ) {
        super();
        this.bindEvent();
        this.menus = menus;
    }


    private _menuMap: {[name: string]: IMenu};
    private target: JQuery;
    private _menus: Array<IMenu> = [];
    private _events: {[event: string]: Function} = {};

    
    public set menus(v : IMenu[]) {
        this._menus = v;
        this.refresh();
    }
    

    public on(event: string, callback: Function): this {
        this._events[event] = callback;
        return this;
    }

    public hasEvent(event: string): boolean {
        return this._events.hasOwnProperty(event);
    }

    public trigger(event: string, ... args: any[]) {
        if (!this.hasEvent(event)) {
            return;
        }
        return this._events[event].call(this, ...args);
    }

    public bindEvent() {
        let _this = this;
        this.box.on('click', 'li', function(e) {
            if (_this.clickLi($(this)) === false) {
                e.stopPropagation();
            }
        });
    }

    public clickLi(li: JQuery) {
        let name = li.attr('data-name'),
            menu: IMenu;
        if (name && this._menuMap.hasOwnProperty(name)) {
            menu = this._menuMap[name];
            if (menu && menu.onclick && menu.onclick(li, this.target, this) === false) {
                return;
            }
        }
        if (name && this.hasEvent(name) && this.trigger(name, li, menu, this.target, this) === false) {
            return;
        }
        return this.trigger('click', li, menu, this.target);
    }

    public addMenu(menu: IMenu | any) {
        this._menus.push(menu);
        this.refresh();
        return this;
    }

    public showPosition(x: number, y: number, target?: JQuery) {
        this.refresh();
        let width = $(window).width(),
            boxWidth = this.box.outerWidth();
        x = Math.max(0, Math.min(x, width - boxWidth));
        y = Math.max(0, Math.min(y, $(window).height() - this.box.outerHeight()));
        this.box.css({
            'left': x + 'px',
            'top': y + 'px'
        }).toggleClass('menu-left', width - x + 80 < 2 * boxWidth ).show();
        this.target = target;
        return this;
    }

    public hide() {
        this.box.hide();
        this.target = null;
    }

    public refresh() {
        this._menuMap = {};
        let menus = this.cleanMenuList(this._menus),
            html = menus ? this.getMenuHtml(menus) : '';
        this.box.html(html);
    }

    /**
     * setChildrenMenu
     */
    public setChildrenMenu(name: string, menus: IMenu[]) {
        if (!this._menuMap.hasOwnProperty(name)) {
            return;
        }
        this._menuMap[name].children = menus;
        this.refresh();
    }

    private getMenuHtml(menus: Array<IMenu>): string {
        let html = '';
        menus.forEach(menu => {
            html += this.getMenuItemHtml(menu);
        });
        return '<ul>'+ html + '</ul>';
    }

    private getMenuItemHtml(menu: IMenu): string {
        let name = (menu.name || menu.text),
            html = '<li data-name="'+  name
        +'"><span>' + (menu.icon ? '<i class="fa fa-'+menu.icon+'"></i>': '') + (menu.text || menu.name),
            menus = this.cleanMenuList(menu.children);
        this._menuMap[name] = menu;
        if (menus && menus.length > 0) {
            return html + '<i class="fa fa-chevron-right"></i></span>' + this.getMenuHtml(menus) + '</li>';
        }
        return html + '</span></li>';
    }

    private cleanMenuList(menus?: Array<IMenu>): Array<IMenu> {
        if (!menus || menus.length == 0) {
            return null;
        }
        let real_menu = [];
        menus.forEach(menu => {
            if (menu.toggle && menu.toggle(this.target, this) === false) {
                return;
            }
            real_menu.push(menu);
        });
        return real_menu;
    }


}

class ChatAddUserBox extends ChatBaseBox {
    /**
     *
     */
    constructor(
        protected parent: ChatRoom
    ) {
        super();
    }

    public box: JQuery;

    protected _user: IUser;

    private _groups: IFriendGroup<IFriend>[];

    public set groups(args: IFriendGroup<IFriend>[]) {
        this._groups = args;
        this.renderGroup();
    }

    public bindEvent() {
        let _this = this;
        this.box.on('click', '.dialog-add-action .dialog-yes', function() {
            _this.parent.trigger(EVENT_ADD_USER, _this._user, _this.find('select').val(), _this);
        });
    }

    protected render() {
        if (!this.box) {
            this.box = this.parent.append(HTML_AGREE_APPLY);
            this.bindEvent();
        }
    }

    public showWithUser(user: IApplyLog) {
        this.render();
        this.parent.trigger(EVENT_USER_GROUPS, this);
        this._user = user.applier || user.user;
        this.refresh();
        this.center().show();
    }

    public refresh() {
        this.find('.dialog-user-avatar img').attr('src', this._user.avatar);
        this.find('.user-name').text(this._user.name);
        this.find('.user-breif').text(this._user.brief);
    }

    /**
     * renderGroup
     */
    public renderGroup() {
        let html = '<option value="">选择分组</option>';
        this._groups.forEach(item => {
            html += '<option value="'+ item.id +'">'+ item.name +'</option>';
        });
        this.find('select').html(html);
    }
    
}

class ChatApplyBox extends ChatAddUserBox {
    /**
     *
     */
    constructor(
        
        protected parent: ChatRoom
    ) {
        super(parent);
    }

    public box: JQuery;

    public bindEvent() {
        let _this = this;
        this.box.on('click', '.dialog-add-action .dialog-yes', function() {
            _this.parent.trigger(EVENT_APPLY_USER, _this._user, _this.find('select').val(), _this.find('textarea').val(), _this);
        });
    }

    protected render() {
        if (!this.box) {
            this.box = this.parent.append(HTML_APPLY);
            this.bindEvent();
        }
    }
    
}

class ChatUserInfoBox extends ChatBaseBox {
    /**
     *
     */
    constructor(
        private parent: ChatRoom
    ) {
        super();
    }

    public box: JQuery;

    private _user: IFriend;

    
    public set user(v : IFriend) {
        this._user = v;
        this.refresh();
    }

    public get user(): IFriend {
        return this._user;
    }
    

    /**
     * showWithUser
     */
    public showWithUser(user: IFriend) {
        if (!this.box) {
            this.box = this.parent.append(HTML_USER_INFO);
        }
        this.user = user;
        this.center().show();
    }

    public refresh() {
        this.find('.dialog-user-avatar img').attr('src', this._user.user.avatar);
        this.find('.user-name').text(this._user.name);
        this.find('.user-breif').text(this._user.user.brief);
    }

}

class ChatApplyLogBox extends ChatBaseBox {
    /**
     *
     */
    constructor(
        private parent: ChatRoom
    ) {
        super();
    }

    public box: JQuery;

    private _users: Array<IApplyLog> = [];

    
    public set users(v : IApplyLog[]) {
        this._users = v;
        this.render();
    }

    public showWithRefresh() {
        if (!this.box) {
            this.box = this.parent.append(HTML_APPLY_LOG);
            this.bindEvent();
        }
        this.parent.trigger(EVENT_APPLY_USERS, this);
        this.show();
    }
    

    private render() {
        let tpl = `<div class="dialog-info" data-id="{0}">
        <div class="dialog-info-avatar">
            <img src="{1}" alt="">
        </div>
        <div class="dialog-info-name">
            <h3>{2}</h3>
            <p>附加消息：{3}</p>
        </div>
        <div class="dialog-action">
            <button>同意</button>
            <button>忽略</button>
        </div>
    </div>`,
        html = '';
        this._users.forEach(user => {
            html += ChatSearchBox.format(tpl, user.id, user.applier.avatar, user.applier.name, user.remark || '');
        });
        this.find('.dialog-apply-list').html(html);
    }

    /**
     * bindEvent
     */
    public bindEvent() {
        let _this = this;
        this.box.on('click', '.dialog-apply-list .dialog-info', function() {
            let user = _this.getUser($(this).data('id'));
            _this.parent.addBox.showWithUser(user);
        });
    }

    public getUser(id: number): IApplyLog {
        for (let i = 0; i < this._users.length; i++) {
            if (this._users[i].id == id) {
                return this._users[i];
            }
        }
        return null;
    }

}

class ChatSearchBox extends ChatBaseBox {
    /**
     *
     */
    constructor(
        private parent: ChatRoom
    ) {
        super();
    }

    public box: JQuery;

    private _is_search_user: boolean = true;

    private _users: Array<IUser> = [];

    
    public set users(v : IUser[]) {
        this._users = v;
        this.render();
    }

    private render() {
        let tpl = `<div class="dialog-info" data-id="{3}">
        <div class="dialog-info-avatar">
            <img src="{0}" alt="">
        </div>
        <div class="dialog-info-name">
            <h3>{1}</h3>
            <p>{2}</p>
        </div>
    </div>`,
        html = '';
        this._users.forEach(user => {
            html += ChatSearchBox.format(tpl, user.avatar, user.name, user.brief || '', user.id);
        });
        this.find('.dialog-search-list').html(html);
    }

    /**
     * bindEvent
     */
    public bindEvent() {
        let _this = this;
        this.box.on('click', '.dialog-search-list .dialog-info', function() {
            let user = _this.getUser($(this).data('id'));
            _this.parent.applyBox.showWithUser({user, id: 0, remark: ''});
        })
        .on('click', '.dialog-tab-header .dialog-tab-item', function() {
            let $this = $(this);
            $this.addClass('active').siblings().removeClass('active');
            _this._is_search_user = $this.index() < 1;
        }).on('keyup', '.dialog-search input', function() {
            _this.parent.trigger(_this._is_search_user 
                ? EVENT_SEARCH_USERS : EVENT_SEARCH_GROUPS, 
                $(this).val(), _this);
        });
    }

    public getUser(id: number): IUser {
        for (let i = 0; i < this._users.length; i++) {
            if (this._users[i].id == id) {
                return this._users[i];
            }
        }
        return null;
    }

    private renderBox() {
        if (!this.box) {
            this.box = this.parent.append(HTML_SEARCH_USER);
            this.bindEvent();
        }
    }

    /**
     * showCenter
     */
    public showCenter() {
        this.renderBox();
        this.center().show();
    }

    /**
     * show
     */
    public show() {
        this.renderBox();
        this.box.show();
        return this;
    }

}

class ChatEditor {
    constructor(
        public box: JQuery,
        private parent: ChatMessageBox
    ) {
        
    }

    public html(): string {
        return this.box.html();
    }

    /**
     * clear
     */
    public clear() {
        this.box.html('');
        return this;
    }

    public text(): string {
        return this.box.text();
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

class ChatMessageBox extends ChatBaseBox {
    /**
     *
     */
    constructor(
        private parent: ChatRoom,
        public send?: IUser,
        public revice?: IFriend
    ) {
        super();
        this.box = this.parent.append(HTML_ROOM);
        this.refresh();
        this.bindEvent();
    }

    public editor: ChatEditor;

    public box: JQuery

    private _messages: Array<IMessage> = [];

    
    public set messages(args: IMessage[]) {
        this._messages = args;
        this.renderMessage();
    }
    

    public refresh() {
        this.editor = new ChatEditor(this.find('.dialog-message-text'), this);
        this.renderTitle();
    }

    /**
     * bindEvent
     */
    public bindEvent() {
        let _this = this;
        this.box.on('click', '.fa-smile-o', function() {
            _this.editor.insertHtmlAtCaret('<img src="./image/avatar.jpg" alt="">');
        }).on('click', '.dailog-message-action button', function() {
            _this.parent.trigger(EVENT_SEND_MESSAGE, _this.editor.text(), _this.revice.user, _this);
        });
        $(window).resize(function() {
            if (!_this.parent.isFixed()) {
                _this.box.removeAttr('style');
                return;
            }
            _this.box.toggleClass('dialog-min', $(window).width() < 500);
            _this.center();
        });
    }

    private renderTitle() {
        if (!this.revice) {
            return;
        }
        this.find('.dialog-title').html('与 '+ this.revice.name +' 聊天中');
    }

    public addMessage(message: IMessage) {
        this._messages.push(message);
        this.renderMessage();
    }

    /**
     * appendMessage
     */
    public appendMessage(messages: Array<IMessage>) {
        this._messages = this._messages.concat(messages);
        this.renderMessage();
    }

    public prependMessage(messages: Array<IMessage>) {
        this._messages = messages.concat(this._messages);
        this.renderMessage();
    }

    private renderMessage() {
        let html = '',
            messages = this.cleanMessage();
        messages.forEach(item => {
            html += this.renderMessageItem(item);
        });
        this.find('.dialog-message-box').html(html);
    }

    private renderMessageItem(item: IMessage) {
        switch (item.type) {
            case ChatType.MORE:
                return '<p class="message-more">加载更多</p>';
            case ChatType.TIME:
                return '<p class="message-line">' + item.content +'</p>';
            case ChatType.TIP:
                return '<p class="message-tip">' + item.content +'</p>';
            case ChatType.MESSAGE:
            default:
                break;
        }
        if (item.user.id == this.send.id) {
            return ChatMessageBox.format(`<div class="message-right">
            <img class="avatar" src="{0}">
            <div class="content">
                {1}
            </div>
        </div>`, item.user.avatar, item.content);
        }
        return ChatMessageBox.format(`<div class="message-left">
        <img class="avatar" src="{0}">
        <div class="content">
            {1}
        </div>
    </div>`, item.user.avatar, item.content);
    }

    private cleanMessage(): Array<IMessage> {
        if (!this._messages || this._messages.length < 1) {
            return [];
        }
        let messages: Array<IMessage> = [
                {
                    type: ChatType.MORE
                }
            ],
            lastTime: number = 0;
        this._messages.forEach(item => {
            if (item.time - lastTime > 200) {
                lastTime = item.time;
                messages.push({
                    content: ChatMessageBox.date(lastTime) + '',
                    type: ChatType.TIME
                });
            }
            messages.push(item);
        });
        return messages;
    }

    /**
     * showWithUser
     */
    public showWithUser(user: IFriend) {
        this.revice = user;
        this.renderTitle();
        if (this.parent.isFixed()) {
            this.center();
        }
        this.show();
        this.messages = [];
        this.parent.trigger(EVENT_GET_MESSAGE, user.user, 1, 10, this);
    }

    public static date(date: Date | number, fmt: string = 'y年m月d日'): string {
        if (typeof date == 'number') {
            date = new Date(date * 1000);
        }
        let o = {
            "y+": date.getFullYear(),
            "m+": date.getMonth() + 1, //月份 
            "d+": date.getDate(), //日 
            "h+": date.getHours(), //小时 
            "i+": date.getMinutes(), //分 
            "s+": date.getSeconds(), //秒 
            "q+": Math.floor((date.getMonth() + 3) / 3), //季度
            "S": date.getMilliseconds() //毫秒 
        };
        for (let k in o) {
            if (new RegExp("(" + k + ")").test(fmt)) {
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            }
        }
        return fmt;
    }


}
/**
 * 会员列表
 */
class ChatUserBox extends ChatBaseBox {
    /**
     *
     */
    constructor(
        private parent: ChatRoom
    ) {
        super();
        this.box = this.parent.append(HTML_MAIN);
        this.menu = new ChatMenu(this.find('.dialog-menu'), USER_MENU);
        this.bindEvent();
    }

    public box: JQuery;
    private _user: IUser;
    private _last_friends: Array<IFriend> = [];
    private _friends: Array<IFriendGroup<IFriend>> = [];
    private _groups: Array<IGroup>;
    public menu: ChatMenu;

    
    public set user(v : IUser) {
        this._user = v;
        this.renderUser();
        this.refresh();
    }

    public get user(): IUser {
        return this._user;
    }
    

    public set friends(args: Array<IFriendGroup<IFriend>>) {
        this._friends = args;
        this.renderFriends();
        args.forEach(group => {
            group.users.forEach(user => {
                if (user.last_message) {
                    this._last_friends.push(user);
                }
            });
        });
        this.renderLastFriends();
        this.refreshMenu();
    }

    public set groups(args: Array<IGroup>) {
        this._groups = args;
        this.renderGroup();
        // args.forEach(user => {
        //     if (user.last_message) {
        //         this._last_friends.push(user);
        //     }
        // });
        this.renderLastFriends();
    }

    public refresh() {
        this.parent.trigger(EVENT_REFRESH_USERS, this);
        this.parent.trigger(EVENT_REFRESH_GROUPS, this);
    }

    public renderGroup() {
        let tpl = `<div class="dialog-user">
        <div class="dialog-user-avatar">
            <img src="{0}" alt="">
        </div>
        <div class="dialog-user-info">
            <p>
                <span class="name">{1}</span>
            </p>
            <p>
                <span class="content">{2}</span>
            </p>
        </div>
    </div>`,
        html = '';
        this._groups.forEach(group => {
            html += ChatUserBox.format(tpl, group.cover, group.name, group.brief);
        });
        this.find('.dialog-tab-box .dialog-tab-item').eq(2).html(html);
    }

    public renderFriends() {
        let panel = `
        <div class="dialog-panel expanded">
        <div class="dialog-panel-header">
            <i class="dialog-panel-icon"></i>
            <span>{0} ({1} / {2})</span>
        </div>
        <div class="dialog-panel-box">
            {3}
        </div>
    </div>
        `,
            tpl = `<div class="dialog-user" data-id="{3}">
            <div class="dialog-user-avatar">
                <img src="{0}" alt="">
            </div>
            <div class="dialog-user-info">
                <p>
                    <span class="name">{1}</span>
                </p>
                <p>
                    <span class="content">{2}</span>
                </p>
            </div>
        </div>`,
        html = '';
        this._friends.forEach(group => {
            let groupHtml = '';
            group.users.forEach(user => {
                groupHtml += ChatUserBox.format(tpl, user.user.avatar, user.name, user.user.brief || '', user.id);
            });
            html += ChatUserBox.format(panel, group.name, group.online_count, group.count, groupHtml);
        });
        this.find('.dialog-tab-box .dialog-tab-item').eq(1).html(html);
    }

    private renderLastFriends() {
        let tpl = `<div class="dialog-user" data-id="{5}">
        <div class="dialog-user-avatar">
            <img src="{0}" alt="">
        </div>
        <div class="dialog-user-info">
            <p>
                <span class="name">{1}</span>
                <span class="time">{2}</span>
            </p>
            <p>
                <span class="content">{3}</span>
                <span class="count">{4}</span>
            </p>
        </div>
    </div>`,
        html = '';
        this._user.new_count = 0;
        this._last_friends.forEach(user => {
            let count = user.new_count || 0;
            this._user.new_count += count;
            html += ChatUserBox.format(tpl, user.user.avatar, user.name, ChatMessageBox.date(user.last_message.time), user.last_message.content, count, user.id);
        });
        this.find('.dialog-tab-box .dialog-tab-item').eq(0).html(html);
        this.renderUser();
    }

    private renderUser() {
        let tpl = `<div class="dialog-info-avatar">
        <img src="{0}" alt="">
    </div>
    <div class="dialog-info-name">
        <h3>{1}</h3>
        <p>{2}</p>
    </div>
    <div class="dialog-message-count">
    {3}
    </div>`;
        this.find('.dialog-info').html(ChatUserBox.format(tpl, this._user.avatar, this._user.name, this._user.brief, this._user.new_count));
    }

    public bindEvent() {
        let _this = this;
        $(document).click(function() {
            _this.menu && _this.menu.hide();
        });
        this.box.click(function() {
            if ($(this).hasClass('dialog-min')) {
                $(this).removeClass('dialog-min');
            }
        }).on('click', '.dialog-header .fa-minus', function(e) {
            e.stopPropagation();
            _this.box.addClass('dialog-min');
        }).on('click', '.dialog-header .fa-plus', function() {
            _this.parent.searchBox.showCenter();
        }).on('click', '.dialog-tab .dialog-tab-header .dialog-tab-item', function() {
            let $this = $(this);
            $this.addClass('active').siblings().removeClass('active');
            $this.closest('.dialog-tab').find('.dialog-tab-box .dialog-tab-item').eq($this.index()).addClass('active').siblings().removeClass('active');
        }).on('click', '.dialog-panel .dialog-panel-header', function() {
            $(this).closest('.dialog-panel').toggleClass('expanded');
        }).on('click', '.dialog-tab .dialog-user', function() {
            let id = $(this).data('id');
            if (!id) {
                return;
            }
            _this.parent.chatBox.showWithUser(_this.getUser(id));
        }).on('contextmenu', '.dialog-tab .dialog-user', function(event) {
            _this.menu && _this.menu.showPosition(event.clientX, event.clientY, $(this));
            return false;
        });
        this.menu.on('click', function(menuLi: JQuery, menu: IMenu, userItem: JQuery) {
            if (menu.name == 'view') {
                _this.parent.userBox.showWithUser(_this.getUser(userItem.data('id')));
            }
        });
        this.parent.on(EVENT_USER_GROUPS, (box: ChatAddUserBox) => {
            box.groups = _this._friends;
        });
    }

    private refreshMenu() {
        let menus = [];
        this._friends.forEach(item => {
            menus.push({
                name: 'group-' + item.id,
                text: item.name
            });
        });
        this.menu.setChildrenMenu('move', menus);
    }

    public getUser(id: number): IFriend {
        for (const item of this._last_friends) {
            if (item.id == id) {
                return item;
            }
        }
        for (const group of this._friends) {
            for (const item of group.users) {
                if (item.id == id) {
                    return item;
                }
            }
        }
    }
}

class ChatRoom {
    constructor(
        public target: JQuery
    ) {
        if (this.target && this.target.length > 0) {
            return;
        }
        this.target = $('<div class="dialog-chat dialog-fixed"></div>');
        $(document.body).append(this.target);
    }
    /**
     * 好友及消息列表
     */
    public mainBox: ChatUserBox;
    /**
     * 接受好友申请
     */
    public addBox: ChatAddUserBox;
    /**
     * 申请加好友
     */
    public applyBox: ChatApplyBox;
    /**
     * 查看用户资料
     */
    public userBox: ChatUserInfoBox;
    /**
     * 搜索
     */
    public searchBox: ChatSearchBox;
    /**
     * 聊天室
     */
    public chatBox: ChatMessageBox;
    /**
     * 申请记录
     */
    public applyLogBox: ChatApplyLogBox;
    private _events: {[event: string]: Function} = {};

    public on(event: string, callback: Function): this {
        this._events[event] = callback;
        return this;
    }

    public hasEvent(event: string): boolean {
        return this._events.hasOwnProperty(event);
    }

    public trigger(event: string, ... args: any[]) {
        if (!this.hasEvent(event)) {
            return;
        }
        return this._events[event].call(this, ...args);
    }

    public init(user: IUser) {
        this.mainBox = new ChatUserBox(this);
        this.addBox = new ChatAddUserBox(this);
        this.applyBox = new ChatApplyBox(this);
        this.userBox = new ChatUserInfoBox(this);
        this.applyLogBox = new ChatApplyLogBox(this);
        this.searchBox = new ChatSearchBox(this);
        this.chatBox = new ChatMessageBox(this, user);
        this.mainBox.user = user;
    }

    /**
     * find
     */
    public find(tag: string): JQuery {
        return this.target.find(tag);
    }

    /**
     * append
     */
    public append(html: string): JQuery {
        const box = $(html);
        this.target.append(box);
        return box;
    }

    /**
     * isFixed
     */
    public isFixed() {
        return this.target.hasClass('dialog-fixed');
    }

    /**
     * toggleMode
     */
    public toggleMode() {
        if (this.isFixed()) {
            return this.page();
        }
        return this.fixed();
    }

    /**
     * fixed
     */
    public fixed() {
        this.target.addClass('dialog-fixed').removeClass('dialog-chat-page');
        this.chatBox.center();
        return this;
    }

    /**
     * page
     */
    public page() {
        this.target.removeClass('dialog-fixed').addClass('dialog-chat-page');
        this.chatBox.box.removeAttr('style');
        return this;
    }
}

const USER_MENU: Array<IMenu> = [
    {
        name: 'view',
        icon: 'eye',
        text: '查看资料'
    },
    {
        name: 'move',
        icon: 'bookmark',
        text: '移动好友',
    },
    {
        name: 'remove',
        icon: 'trash',
        text: '删除好友'
    },
];

function registerChat() {
    let room = new ChatRoom($('.dialog-chat'));
    room.on(EVENT_REFRESH_USERS, (box: ChatUserBox) => {
        postJson(BASE_URI + 'friend', function(data) {
            if (data.code != 200) {
                return;
            }
            box.friends = data.data;
        });
    }).on(EVENT_REFRESH_GROUPS, (box: ChatUserBox) => {
        postJson(BASE_URI + 'group', function(data) {
            if (data.code != 200) {
                return;
            }
            box.groups = data.data;
        });
    }).on(EVENT_SEARCH_USERS, (keywords: string, box: ChatSearchBox) => {
        postJson(BASE_URI + 'friend/search', {
            keywords: keywords
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.users = data.data.data;
        });
    }).on(EVENT_GET_MESSAGE, (user: IUser, page: number, per_page: number, box: ChatMessageBox) => {
        postJson(BASE_URI + 'friend/message', {
            user: user.id,
            page: page,
            per_page: per_page,
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.messages = data.data.data;
        });
    }).on(EVENT_SEND_MESSAGE, (content: string, user: IUser, box: ChatMessageBox) => {
        postJson(BASE_URI + 'friend/send_message', {
            user: user.id,
            content: content
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.addMessage({
                content: content,
                type: ChatType.MESSAGE,
                user: room.mainBox.user
            });//data.data.data;
            box.editor.clear();
        });
    }).on(EVENT_APPLY_USER, (user: IUser, group: number, remark: string, box: ChatApplyBox) => {
        postJson(BASE_URI + 'friend/apply', {
            user: user.id,
            group: group,
            remark: remark
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.hide();
        });
    }).on(EVENT_ADD_USER, (user: IUser, group: number, box: ChatAddUserBox) => {
        postJson(BASE_URI + 'friend/agree', {
            user: user.id,
            name: user.name,
            group: group
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.hide();
        });
    }).on(EVENT_APPLY_USERS, (box: ChatApplyLogBox) => {
        postJson(BASE_URI + 'friend/apply_log', function(data) {
            if (data.code != 200) {
                return;
            }
            box.users = data.data;
        });
    });
    postJson(BASE_URI + 'user', function(data) {
        if (data.code === 200) {
            room.init(data.data);
            loopPing();
        }
    });
    
    room.target.on('click', '.dialog-header .fa-close', function() {
        $(this).closest('.dialog-box').hide();
    });
    $('#toggle-btn').click(function() {
        room.toggleMode();
    });
    let handle;
    let lastTime;
    let loopPing = function() {
        postJson(BASE_URI + 'message/ping', {
            time: lastTime || 0,
            user: room.chatBox.revice ? room.chatBox.revice.id : 0
        }, function(data) {
            if (data.code === 200) {
                const args = data.data;
                doEvent('message_count', args.message);
                if (args.apply > 0) {
                    doEvent('apply');
                }
                if (args.messages && args.messages.length > 0) {
                    doEvent('message', args.messages);
                }
                lastTime = data.data.time;
            }
            handle = setTimeout(loopPing, 10000);
        })
    }, doEvent = function(event: string, data?: any) {
        if (event === 'message_count') {
            room.mainBox.user.new_count = data;
            return;
        }
        if (event === 'apply') {
            room.applyLogBox.showWithRefresh();
            return;
        }
        if (event === 'message') {
            room.chatBox.appendMessage(data);
            return;
        }
    };

}

function registerWsChat(baseUri: string) {
    let room = new ChatRoom($('.dialog-chat'));
    let socket = new Ws(baseUri);
    socket.onConnect(() => {

    }).onDisconnect(() => {

    }).on('message', () => {

    });

    room.on(EVENT_REFRESH_USERS, (box: ChatUserBox) => {
        socket.emit(EVENT_REFRESH_USERS, true);
    }).on(EVENT_REFRESH_GROUPS, (box: ChatUserBox) => {
        socket.emit(EVENT_REFRESH_GROUPS, true);
    }).on(EVENT_SEARCH_USERS, (keywords: string, box: ChatSearchBox) => {
        socket.emit(EVENT_SEARCH_USERS, keywords);
    }).on(EVENT_GET_MESSAGE, (user: IUser, page: number, per_page: number, box: ChatMessageBox) => {
        socket.emit(EVENT_GET_MESSAGE, {page, per_page, user: user.id});
    }).on(EVENT_SEND_MESSAGE, (content: string, user: IUser, box: ChatMessageBox) => {
        socket.emit(EVENT_SEND_MESSAGE, {content, user: user.id});
    }).on(EVENT_APPLY_USER, (user: IUser, group: number, remark: string, box: ChatApplyBox) => {
        postJson(BASE_URI + 'friend/apply', {
            user: user.id,
            group: group,
            remark: remark
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.hide();
        });
    }).on(EVENT_ADD_USER, (user: IUser, group: number, box: ChatAddUserBox) => {
        postJson(BASE_URI + 'friend/agree', {
            user: user.id,
            group: group
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.hide();
        });
    });
    
    $('.dialog-box').on('click', '.dialog-header .fa-close', function() {
        $(this).closest('.dialog-box').hide();
    });
    $('#toggle-btn').click(function() {
        room.toggleMode();
    });
}

enum WsMessageType {
    STRING,
    INT,
    BOOL,
    JSON = 4,
}

interface IWsOption {
    prefix?: string;
    separator?: string;
}

interface IWsMessageListeners {
    [key: string]: Function[];
}

class Ws {
    constructor(endpoint: string, protocols?: any) {
        if (!window['WebSocket']) {
            throw '不支持ws';
        }
        if (endpoint.indexOf("ws") == -1) {
            endpoint = "ws://" + endpoint;
        }
        if (protocols && protocols.length > 0) {
            this.conn = new WebSocket(endpoint, protocols);
        }
        else {
            this.conn = new WebSocket(endpoint);
        }
        this.conn.onopen = () => {
            this.fireConnect();
            this.isReady = true;
            return null;
        };
        this.conn.onclose = () => {
            this.fireDisconnect();
            return null;
        };
        this.conn.onmessage = (evt: MessageEvent<any>) => {
            this.messageReceivedFromConn(evt);
        };
    }

    private option: IWsOption = {
        prefix: 'ws-message:',
        separator: ';',
    };

    private conn: WebSocket;

    private isReady: boolean = false;

    private connectListeners: Function[] = [];

    private disconnectListeners: Function[] = [];

    private nativeMessageListeners: Function[] = [];

    private messageListeners: IWsMessageListeners = {};

    public isNumber(obj: any): boolean {
        return !isNaN(obj - 0) && obj !== null && obj !== "" && obj !== false;
    }

    public isString(obj: any): boolean {
        return Object.prototype.toString.call(obj) == "[object String]";
    }

    public isBoolean(obj: any) {
        return typeof obj === 'boolean' ||
            (typeof obj === 'object' && typeof obj.valueOf() === 'boolean');
    }

    public isJSON(obj: string) {
        return typeof obj === 'object';
    }

    public toMsg(event: string, websocketMessageType: WsMessageType, dataMessage: string) {
        return this.option.prefix + event + this.option.separator + String(websocketMessageType) + this.option.separator + dataMessage;
    }
    public encodeMessage(event: string, data: any) {
        let m = '';
        let t = WsMessageType.STRING;
        if (this.isNumber(data)) {
            t = WsMessageType.INT;
            m = data.toString();
        }
        else if (this.isBoolean(data)) {
            t = WsMessageType.BOOL;
            m = data.toString();
        }
        else if (this.isString(data)) {
            t = WsMessageType.STRING
            m = data.toString();
        }
        else if (this.isJSON(data)) {
            t = WsMessageType.JSON;
            m = JSON.stringify(data);
        }
        else if (data !== null && typeof(data) !== "undefined" ) {
            console.log("unsupported type of input argument passed, try to not include this argument to the 'Emit'");
        }
        return this.toMsg(event, t, m);
    }
    public decodeMessage(event, websocketMessage) {
        //iris-websocket-message;user;4;themarshaledstringfromajsonstruct
        const skipLen = this.option.prefix.length + this.option.separator.length + event.length + 2;
        if (websocketMessage.length < skipLen + 1) {
            return null;
        }
        const websocketMessageType = parseInt(websocketMessage.charAt(skipLen - 2));
        const theMessage = websocketMessage.substring(skipLen, websocketMessage.length);
        if (websocketMessageType === WsMessageType.INT) {
            return parseInt(theMessage);
        }
        if (websocketMessageType == WsMessageType.BOOL) {
            return Boolean(theMessage);
        }
        if (websocketMessageType == WsMessageType.STRING) {
            return theMessage;
        }
        if (websocketMessageType == WsMessageType.JSON) {
            return JSON.parse(theMessage);
        }
        return null;
    }
    public getWebsocketCustomEvent(websocketMessage: string) {
        const websocketMessagePrefixAndSepIdx = this.option.prefix.length + this.option.separator.length - 1;
        if (websocketMessage.length < websocketMessagePrefixAndSepIdx) {
            return '';
        }
        const s = websocketMessage.substring(websocketMessagePrefixAndSepIdx, websocketMessage.length);
        return s.substring(0, s.indexOf(this.option.separator));
    }
    public getCustomMessage(event: string, websocketMessage: any) {
        const eventIdx = websocketMessage.indexOf(event + this.option.separator);
        return websocketMessage.substring(eventIdx + event.length + this.option.separator.length + 2, websocketMessage.length);
    }
    public messageReceivedFromConn(evt: MessageEvent<string>) {
        const message = evt.data;
        if (message.indexOf(this.option.prefix) != -1) {
            var event_1 = this.getWebsocketCustomEvent(message);
            if (event_1 != "") {
                // it's a custom message
                this.fireMessage(event_1, this.getCustomMessage(event_1, message));
                return;
            }
        }
        this.fireNativeMessage(message);
    }
    public onConnect(fn: Function) {
        if (this.isReady) {
            fn();
        }
        this.connectListeners.push(fn);
        return this;
    }
    public fireConnect() {
        this.connectListeners.forEach(cb => {
            cb();
        });
    }
    public onDisconnect(fn: Function) {
        this.disconnectListeners.push(fn);
        return this;
    }
    public fireDisconnect() {
        this.disconnectListeners.forEach(cb => {
            cb();
        });
    }
    public onMessage(cb: Function) {
        this.nativeMessageListeners.push(cb);
        return this;
    }
    public fireNativeMessage(websocketMessage: any) {
        this.nativeMessageListeners.forEach(cb => {
            cb(websocketMessage);
        });
        return this;
    }
    public on(event: string, cb: Function) {
        if (this.messageListeners[event] == null || this.messageListeners[event] == undefined) {
            this.messageListeners[event] = [];
        }
        this.messageListeners[event].push(cb);
        return this;
    }
    public fireMessage(event: string, message: any) {
        const listeners = this.messageListeners[event];
        if (!listeners) {
            return;
        }
        listeners.forEach(cb => {
            cb(message);
        });
    }
    public disconnect() {
        this.conn.close();
    }
    public emitMessage(websocketMessage: any) {
        this.conn.send(websocketMessage);
    }
    public emit(event: string, data: any) {
        this.emitMessage(this.encodeMessage(event, data));
    }
}