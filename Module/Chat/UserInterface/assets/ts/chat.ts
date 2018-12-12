interface IMenu {
    name?: string,
    text?: string,
    icon?: string,
    toggle?: (target: JQuery, menu: ChatMenu)=>boolean,
    onclick?: (menuItem: JQuery, target: JQuery, menu: ChatMenu) => void| boolean,
    children?: Array<IMenu>
}

interface IGroup {
    id?: number,
    name: string,
    count?: number,
    online_count?: number,
    users: Array<IUser>
}

interface IUser {
    id: number,
    name: string,
    avatar: string,
    brief: string,
    new_count?: number,
    last_message?: IMessage
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
    EVENT_ADD_USER = 'add_user',
    EVENT_SEND_MESSAGE = 'send_message',
    EVENT_GET_MESSAGE = 'get_message';

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
        public box: JQuery,
        private parent: ChatRoom
    ) {
        super();
        this.bindEvent();
    }

    private _user: IUser;

    private _groups: IGroup[];

    public set groups(args: IGroup[]) {
        this._groups = args;
        this.renderGroup();
    }

    public bindEvent() {
        let _this = this;
        this.box.on('click', '.dialog-add-action .dialog-yes', function() {
            _this.parent.trigger(EVENT_ADD_USER, _this._user, _this.find('select').val(), _this);
        });
    }

    public showWithUser(user: IUser) {
        this.parent.trigger(EVENT_USER_GROUPS, this);
        this._user = user;
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

class ChatUserInfoBox extends ChatBaseBox {
    /**
     *
     */
    constructor(
        public box: JQuery,
        private parent: ChatRoom
    ) {
        super();
    }

    private _user: IUser;

    
    public set user(v : IUser) {
        this._user = v;
        this.refresh();
    }
    

    /**
     * showWithUser
     */
    public showWithUser(user: IUser) {
        this.user = user;
        this.center().show();
    }

    public refresh() {
        this.find('.dialog-user-avatar img').attr('src', this._user.avatar);
        this.find('.user-name').text(this._user.name);
        this.find('.user-breif').text(this._user.brief);
    }

}

class ChatSearchBox extends ChatBaseBox {
    /**
     *
     */
    constructor(
        public box: JQuery,
        private parent: ChatRoom
    ) {
        super();
        this.bindEvent();
    }

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
            html += ChatSearchBox.format(tpl, user.avatar, user.name, user.brief, user.id);
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
            _this.parent.addBox.showWithUser(user);
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
        public box: JQuery,
        private parent: ChatRoom,
        public send?: IUser,
        public revice?: IUser
    ) {
        super();
        this.refresh();
        this.bindEvent();
    }

    public editor: ChatEditor;

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
            _this.parent.trigger(EVENT_SEND_MESSAGE, _this.editor.text(), _this.revice, _this);
        });
        $(window).resize(function() {
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
    public showWithUser(user: IUser) {
        this.revice = user;
        this.renderTitle();
        this.center().show();
        this.messages = [];
        this.parent.trigger(EVENT_GET_MESSAGE, user, 1, 10, this);
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

class ChatUserBox extends ChatBaseBox {
    /**
     *
     */
    constructor(
        public box: JQuery,
        private parent: ChatRoom
    ) {
        super();
        this.menu = new ChatMenu(this.find('.dialog-menu'), USER_MENU);
        this.bindEvent();
    }

    private _user: IUser;
    private _last_friends: Array<IUser> = [];
    private _friends: Array<IGroup>;
    private _groups: Array<IUser>;
    public menu: ChatMenu;

    
    public set user(v : IUser) {
        this._user = v;
        this.renderUser();
        this.refresh();
    }
    

    public set friends(args: Array<IGroup>) {
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

    public set groups(args: Array<IUser>) {
        this._groups = args;
        this.renderGroup();
        args.forEach(user => {
            if (user.last_message) {
                this._last_friends.push(user);
            }
        });
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
            html += ChatUserBox.format(tpl, group.avatar, group.name, group.brief);
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
            tpl = `<div class="dialog-user">
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
                groupHtml += ChatUserBox.format(tpl, user.avatar, user.name, user.brief);
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
            html += ChatUserBox.format(tpl, user.avatar, user.name, ChatMessageBox.date(user.last_message.time), user.last_message.content, count, user.id);
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
            _this.parent.searchBox.center().show();
        }).on('click', '.dialog-tab .dialog-tab-header .dialog-tab-item', function() {
            let $this = $(this);
            $this.addClass('active').siblings().removeClass('active');
            $this.closest('.dialog-tab').find('.dialog-tab-box .dialog-tab-item').eq($this.index()).addClass('active').siblings().removeClass('active');
        }).on('click', '.dialog-panel .dialog-panel-header', function() {
            $(this).closest('.dialog-panel').toggleClass('expanded');
        }).on('click', '.dialog-tab .dialog-user', function() {
            _this.parent.chatBox.showWithUser(_this.getUser($(this).data('id')));
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

    public getUser(id: number): IUser {
        for (let i = 0; i < this._last_friends.length; i++) {
            if (this._last_friends[i].id == id) {
                return this._last_friends[i];
            }
        }
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
        this.mainBox = new ChatUserBox(this.target.find('.dialog-chat-box'), this);
        this.addBox = new ChatAddUserBox(this.target.find('.dialog-add-box'), this);
        this.userBox = new ChatUserInfoBox(this.target.find('.dialog-user-box'), this);
        this.searchBox = new ChatSearchBox(this.target.find('.dialog-search-box'), this);
        this.chatBox = new ChatMessageBox(this.target.find('.dialog-chat-room'), this, user);
        this.mainBox.user = user;
    }

    /**
     * toggleMode
     */
    public toggleMode() {
        if (this.target.hasClass('dialog-fixed')) {
            return this.page();
        }
        return this.fixed();
    }

    /**
     * fixed
     */
    public fixed() {
        this.target.addClass('dialog-fixed').removeClass('dialog-chat-page');
        return this;
    }

    /**
     * page
     */
    public page() {
        this.target.removeClass('dialog-fixed').addClass('dialog-chat-page');
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

const TEST_USER: IUser = {
        id: 2,
        name: '123123',
        brief: '12313',
        avatar: '/assets/images/avatar/14.png',
        new_count: 1,
        last_message: {
            content: '123123',
            time: 1510601234,
            type: ChatType.MESSAGE
        }
    },
    TEST_SEND: IUser = {
        id: 1,
        name: 'admin',
        brief: '1111',
        avatar: '/assets/images/avatar/1.png',
        new_count: 1,
    },
    TEST_GROUP: IGroup = {
        id: 1,
        name: '我的好友',
        count: 2,
        online_count: 1,
        users: [
            TEST_SEND,
            TEST_USER
        ]
    },
    TEST_MESSAGE: IMessage = {
        content: '123123',
        time: 1510601234,
        type: ChatType.MESSAGE,
        user: TEST_USER
    };


function registerChat(baseUri: string) {
    let room = new ChatRoom($('.dialog-chat'));
    room.on(EVENT_REFRESH_USERS, (box: ChatUserBox) => {
        postJson(baseUri + 'friend', function(data) {
            if (data.code != 200) {
                return;
            }
            box.friends = [TEST_GROUP];//data.data;
        });
    }).on(EVENT_REFRESH_GROUPS, (box: ChatUserBox) => {
        postJson(baseUri + 'group', function(data) {
            if (data.code != 200) {
                return;
            }
            box.groups = [TEST_USER]//data.data;
        });
    }).on(EVENT_SEARCH_USERS, (keywords: string, box: ChatSearchBox) => {
        postJson(baseUri + 'friend/search', {
            keywords: keywords
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.users = [TEST_USER];//data.data.data;
        });
    }).on(EVENT_GET_MESSAGE, (user: IUser, page: number, per_page: number, box: ChatMessageBox) => {
        postJson(baseUri + 'friend/message', {
            user: user.id,
            page: page,
            per_page: per_page,
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.messages = [TEST_MESSAGE];//data.data.data;
        });
    }).on(EVENT_SEND_MESSAGE, (content: string, user: IUser, box: ChatMessageBox) => {
        postJson(baseUri + 'friend/send_message', {
            user: user.id,
            content: content
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.addMessage({
                content: content,
                type: ChatType.MESSAGE,
                user: TEST_SEND
            });//data.data.data;
        });
    }).on(EVENT_ADD_USER, (user: IUser, group: number, box: ChatAddUserBox) => {
        postJson(baseUri + 'friend/apply', {
            user: user.id,
            group: group
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            box.hide();
        });
    }).init(TEST_SEND);
    
    $('.dialog-box').on('click', '.dialog-header .fa-close', function() {
        $(this).closest('.dialog-box').hide();
    });
    $('#toggle-btn').click(function() {
        room.toggleMode();
    });
}