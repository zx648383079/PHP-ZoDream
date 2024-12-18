/// <reference types="jquery" />

declare namespace ZreDialog {
    /**
    * 缓存数据
    */
    class CacheUrl {
        /**
        * 缓存的数据
        */
        private static _cacheData;
        /**
        * 缓存的事件
        */
        private static _event;
        static hasData(url: string): boolean;
        static hasEvent(url: string): boolean;
        static addEvent(url: string, callback: (data: any) => void): void;
        /**
        * 获取数据通过回调返回
        * @param url
        * @param callback
        */
        static getData(url: string, callback: (data: any) => void): void;
        /**
        * 设置数据并回调
        * @param url
        * @param data
        */
        static setData(url: string, data: any): void;
    }
    abstract class Eve {
        options: any;
        on(event: string, callback: Function): this;
        hasEvent(event: string): boolean;
        trigger(event: string, ...args: any[]): any;
    }
    abstract class Box extends Eve {
        element: JQuery;
        box: JQuery;
        protected showPosition(): this;
        /**
        * 自适应布局
        */
        protected setPosition(): this;
        /**
        * 根据可能是相对值获取绝对值
        * @param abservable
        * @param reltive
        */
        static getReal(abservable: number, reltive: number): number;
    }
    /**
    * 已知问题
    * 如果一个不能关闭， 多个将出现错乱
    */
    abstract class DialogCore extends Box implements DialogInterfae {
        id?: number;
        constructor(option: DialogOption, id?: number);
        options: DialogOption;
        private _status;
        get status(): DialogStatus;
        set status(arg: DialogStatus);
        private _dialogBg;
        private _y;
        get y(): number;
        set y(y: number);
        private _height;
        get height(): number;
        set height(height: number);
        /**
        * 改变状态
        * @param status
        * @param hasEvent
        */
        protected changeStatus(status: DialogStatus): void;
        /**
        * 获取默认设置
        */
        protected getDefaultOption(): DialogOption;
        /**
        * 创建并显示控件
        */
        protected showBox(): boolean;
        protected doShowStatus(): void;
        /**
        * 创建并隐藏控件
        */
        protected hideBox(): boolean;
        protected doHideStatus(): void;
        /**
        * 动画关闭，有关闭动画
        */
        protected closingBox(): boolean;
        protected doClosingStatus(): void;
        /**
        * 删除控件
        */
        protected closeBox(): boolean;
        protected doCloseStatus(): void;
        abstract init(): any;
        protected createCore(): this;
        protected abstract createContent(): this;
        protected abstract setProperty(): this;
        css(key: any, value?: string | number): this;
        show(): this;
        hide(): this;
        close(hasAnimation?: boolean): this;
        toggle(): this;
        /**
        * 获取相同类型弹出框的最上面
        */
        protected getDialogTop(): number | undefined;
        protected getDialogBottom(): number | undefined;
        private _getBottom;
        private _getTop;
        private _getLeft;
        private _getRight;
        private _getWidth;
        private _getHeight;
        private _getLeftTop;
        x: number;
        top(top?: number): number | this;
        left(left?: number): number | this;
        width(width?: number): number | this;
        addClass(name: string): this;
        hasClass(name: string): boolean;
        removeClass(name: string): this;
        find(name: string): JQuery;
        isElement(content: any): boolean;
    }
    class DefaultDialogOption implements DialogOption {
        title: string;
        type?: DialogType;
        canMove: boolean;
        closeAnimate: boolean;
        ondone: Function;
    }
    class Dialog {
        static methods: {
            [type: number]: Function;
        };
        private static _data;
        private static _guid;
        private static _tipData;
        private static _dialogBg;
        private static _bgLock;
        static $window: JQuery<Window & typeof globalThis>;
        /**
        * 创造弹出框
        * @param option
        */
        static create<T>(option?: DialogOption): T;
        static bind(box: JQuery): DialogContent;
        static parseEnum<T>(val: any, type: any): T;
        /**
        * 提示
        * @param content
        * @param time
        */
        static tip(content: string | DialogTipOption, time?: number): DialogTip;
        /**
        * 消息
        * @param content
        * @param time
        */
        static message(content: string | DialogMessageOption, time?: number): DialogMessage;
        static pop(content: string | DialogPopOption, target: JQuery, time?: number): DialogPop;
        /**
        * 加载
        * @param time
        */
        static loading(time?: number | DialogOption): DialogLoading;
        /**
        * 内容弹窗
        * @param content
        * @param hasYes
        * @param hasNo
        */
        static content(content: string | DialogOption, hasYes?: boolean, hasNo?: boolean): DialogContent;
        /**
        * 普通弹窗
        * @param content
        * @param title
        * @param hasYes
        * @param hasNo
        */
        static box(content: string | DialogOption, title?: string, hasYes?: boolean, hasNo?: boolean): DialogBox;
        /**
        * 表格弹窗
        * @param content
        * @param title
        * @param done
        * @param hasYes
        * @param hasNo
        */
        static form(content: any, title?: string, done?: Function, hasYes?: boolean, hasNo?: boolean): DialogForm;
        /**
        * 页面弹窗
        * @param content
        * @param title
        * @param hasYes
        * @param hasNo
        */
        static page(content: string | DialogOption, title?: string, hasYes?: boolean, hasNo?: boolean): DialogPage;
        /**
        * 桌面提醒
        * @param title
        * @param content
        * @param icon
        */
        static notify(title?: string | DialogOption, content?: string, icon?: string): DialogNotify;
        /**
        * 添加弹出框
        * @param element
        */
        static addItem(element: DialogCore): void;
        static hasItem(id?: number | string): boolean;
        static get(id?: number | string): any;
        /**
        * 根据id删除弹出框
        * @param id
        */
        static removeItem(id?: number): void;
        /**
        * 删除所有弹出框
        */
        static remove(): void;
        /**
        * 循环所有弹出框
        * @param callback
        */
        static map(callback: (item: DialogCore) => any): void;
        /**
        * 显示遮罩
        */
        static showBg(target?: JQuery, isPublic?: boolean): void;
        /**
        * 隐藏遮罩
        */
        static closeBg(): void;
        static addMethod(type: DialogType, dialog: Function): void;
        static hasMethod(type: DialogType): boolean;
        static getMethod(type: DialogType): Function;
    }
    interface DialogInterfae {
        status: DialogStatus;
        x: number;
        y: number;
        /**
        * 显示
        */
        show(): this;
        /**
        * 隐藏
        */
        hide(): this;
        /**
        * 关闭
        */
        close(): this;
        /**
        * 显示隐藏切换
        */
        toggle(): this;
        /**
        * 设置css
        */
        css(key: any, value?: string | number): this;
        top(top?: number): this | number;
        left(left?: number): this | number;
        width(width?: number): this | number;
        addClass(name: string): this;
        hasClass(name: string): boolean;
        removeClass(name: string): this;
        find(name: string): JQuery;
        on(event: string, callback: Function): this;
    }
    /**
    * 弹出框类型
    */
    enum DialogType {
        tip = 0,
        message = 1,
        notify = 2,
        pop = 3,
        loading = 4,
        select = 5,
        image = 6,
        disk = 7,
        form = 8,
        content = 9,
        box = 10,
        page = 11
    }
    /**
    * 弹出框位置
    */
    enum DialogDirection {
        top = 0,
        right = 1,
        bottom = 2,
        left = 3,
        center = 4,
        leftTop = 5,
        rightTop = 6,
        rightBottom = 7,
        leftBottom = 8
    }
    /**
    * 弹出框状态
    */
    enum DialogStatus {
        hide = 0,
        show = 1,
        closing = 2,
        closed = 3
    }
    enum DialogDiskType {
        file = 0,
        directory = 1
    }
    const DIALOG_SHOW = "dialog_show";
    const DIALOG_HIDE = "dialog_hide";
    const DIALOG_CLOSE = "dialog_close";
    const DIALOG_CLOSING = "dialog_closing";
    const DIALOG_LOADED = "dialog_loaded";
    const DIALOG_ASYNC = "dialog_async";
    const DIALOG_DONE = "dialog_done";
    const _DIALOG_DONE = "done";
    const _DIALOG_CANCEL = "cancel";
    const _DIALOG_SHOW = "show";
    const _DIALOG_HIDE = "hide";
    const _DIALOG_CLOSE = "closed";
    const _DIALOG_CLOSING = "closing";
    interface DialogOption {
        [setting: string]: any;
        content?: string;
        type?: string | number | DialogType;
        closeAnimate?: boolean;
        target?: JQuery;
        onclosing?: () => any;
    }
    class DialogPlugin {
        element: JQuery;
        option?: DialogOption;
        constructor(element: JQuery, option?: DialogOption);
        dialog: DialogCore;
        getDialog(ele?: JQuery): DialogCore;
        private _parseOption;
        /**
        * close
        */
        close(): this;
        /**
        * show
        */
        show(): this;
        /**
        * hide
        */
        hide(): this;
        /**
        *
        */
        toggle(): this;
        /**
        *
        * @param tag
        */
        find(tag: string): JQuery<HTMLElement>;
        /**
        * on
        */
        on(event: string, func: Function): this;
    }
    interface DialogTipOption extends DialogOption {
        time?: number;
    }
    class DialogTip extends DialogCore {
        constructor(option: DialogOption, id?: number);
        options: DialogTipOption;
        private _timeHandle;
        init(): void;
        protected getDefaultOption(): DefaultDialogTipOption;
        /**
        * 设置内容
        */
        protected createContent(): this;
        /**
        * 添加到容器上
        */
        protected appendParent(): this;
        /**
        * 设置属性
        */
        protected setProperty(): this;
        /**
        * 绑定事件
        */
        protected bindEvent(): this;
        /**
        * 重设尺寸
        */
        resize(): void;
        protected addTime(): void;
        protected stopTime(): void;
        protected closingBox(): boolean;
        protected closeBox(): boolean;
        protected changeOther(): void;
    }
    class DefaultDialogTipOption implements DialogTipOption {
        time: number;
    }
    interface DialogMessageOption extends DialogTipOption {
    }
    class DialogMessage extends DialogTip {
        constructor(option: DialogOption, id?: number);
        protected setProperty(): this;
        protected bindEvent(): this;
        protected changeOther(): void;
    }
    interface DialogNotifyOption extends DialogTipOption {
        title?: string;
        ico?: string;
    }
    class DialogNotify extends DialogTip {
        constructor(option: DialogNotifyOption, id?: number);
        options: DialogNotifyOption;
        notify: Notification;
        protected createContent(): this;
        protected setProperty(): this;
        /**
        * 获取默认设置
        */
        protected getDefaultOption(): DefaultDialogNotifyOption;
        protected showBox(): boolean;
        protected hideBox(): boolean;
        protected closingBox(): boolean;
        protected closeBox(): boolean;
        private _createNotify;
        private _closeNotify;
    }
    class DefaultDialogNotifyOption extends DefaultDialogTipOption implements DialogNotifyOption {
        title: string;
    }
    interface DialogPopOption extends DialogTipOption {
        direction?: DialogDirection | string | number;
    }
    class DialogPop extends DialogTip {
        constructor(option: DialogPopOption, id?: number);
        protected setProperty(): this;
        /**
        * 添加到容器上
        */
        protected appendParent(): this;
        protected bindEvent(): this;
        private _getRandomDirection;
        private _setPopProperty;
        private _getPopLeftTop;
    }
    interface DialogLoadingOption extends DialogTipOption {
        count?: number;
        extra?: string;
    }
    class DialogLoading extends DialogTip {
        constructor(option: DialogLoadingOption, id?: number);
        protected getDefaultOption(): DefaultDialogLoadingOption;
        protected createContent(): this;
        protected setProperty(): this;
        private _getLoading;
        protected showBox(): boolean;
        protected hideBox(): boolean;
        protected closingBox(): boolean;
        protected closeBox(): boolean;
    }
    class DefaultDialogLoadingOption implements DialogLoadingOption {
        extra: string;
        count: number;
        time: number;
    }
    interface DialogButton {
        content: string;
        tag?: string;
    }
    interface DialogContentOption extends DialogOption {
        url?: string;
        button?: string | string[] | DialogButton[];
        hasYes?: boolean | string;
        hasNo?: boolean | string;
        ondone?: Function;
    }
    class DialogContent extends DialogCore {
        constructor(option: DialogContentOption, id?: number);
        private _isLoading;
        private _loadingDialog;
        get isLoading(): boolean;
        set isLoading(arg: boolean);
        /**
        * 显示加载动画
        */
        private _toggleLoading;
        /**
        * 是不是固定的
        */
        protected isFixedBox(): boolean;
        init(): void;
        protected getDefaultOption(): DefaultDialogContentOption;
        /**
        * 设置内容
        */
        protected createContent(): this;
        /**
        * 添加到容器上
        */
        protected appendParent(): this;
        /**
        * 设置属性
        */
        protected setProperty(): this;
        /**
        * 绑定事件
        */
        protected bindEvent(): this;
        protected getContentHtml(): string;
        protected getFooterHtml(): string;
        onClick(tag: string, callback: (element: JQuery) => any): this;
        protected showBox(): boolean;
        protected hideBox(): boolean;
        protected closingBox(): boolean;
        protected closeBox(): boolean;
    }
    class DefaultDialogContentOption implements DialogContentOption {
        hasYes: boolean;
        hasNo: boolean;
        time: number;
        button: string[];
    }
    interface DialogBoxOption extends DialogContentOption {
        ico?: string;
        title?: string;
        canMove?: boolean;
    }
    class DialogBox extends DialogContent {
        constructor(option: DialogBoxOption, id?: number);
        /**
        * 设置内容
        */
        protected createContent(): this;
        protected setProperty(): this;
        protected bindEvent(): this;
        /**
        * 重设尺寸
        */
        resize(): void;
        showCenter(): this;
        protected getDefaultOption(): DefaultDialogBoxOption;
        protected getHeaderHtml(): string;
    }
    class DefaultDialogBoxOption extends DefaultDialogContentOption implements DialogBoxOption {
        title: string;
        canMove: boolean;
    }
    class DialogForm extends DialogBox {
        constructor(option: DialogOption, id?: number);
        private _data;
        /**
        * 表单数据
        */
        get data(): {
            [name: string]: string | string[];
        };
        private _elements;
        /**
        * 表单控件
        */
        get elements(): {
            [name: string]: JQuery;
        };
        protected getContentHtml(): string;
        private _createForm;
        private _createInput;
        /**
        * 获取表单控件
        */
        private _getFormElement;
        /**
        * 获取表单数据
        */
        private _getFormData;
    }
    class DialogPage extends DialogBox {
        constructor(option: DialogOption, id?: number);
        protected getHeaderHtml(): string;
        /**
        * 绑定事件
        */
        protected bindEvent(): this;
    }
    interface DialogImageOption extends DialogOption {
        onnext?: (index: number) => string;
        onprevious?: (index: number) => string;
    }
    class DialogImage extends DialogContent {
        constructor(option: DialogOption, id?: number);
        private _index;
        private _target;
        private _src;
        private _imageWidth;
        private _imageHeight;
        get src(): string;
        set src(img: string);
        init(): void;
        protected createContent(): this;
        protected setProperty(): this;
        private resetWithImage;
        /**
        * 绑定事件
        */
        protected bindEvent(): this;
        showIndex(index: number): void;
        showImg(src: string): void;
        /**
        * 重设尺寸
        */
        resize(): void;
        previous(): void;
        next(): void;
        protected getContentHtml(): string;
    }
    class DefaultDialogImageOption implements DialogImageOption {
        onrequest: (index: number) => string;
    }
}

declare class Dialog extends ZreDialog.Dialog {}
