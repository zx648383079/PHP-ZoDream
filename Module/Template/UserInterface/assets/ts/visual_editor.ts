const EditorEventGetWeights = 'editor_get_wegihts';
const EditorEventGetWeightProperty = 'editor_get_wegiht_property';
const EditorEventSaveWeightProperty = 'editor_save_wegiht_property';
const EditorEventRefreshWeight = 'editor_refresh_weight';
const EditorEventAddWeight = 'editor_add_weight';
const EditorEventMoveWeight = 'editor_move_weight';
const EditorEventWeightForm = 'editor_weight_form';
const EditorEventRemoveWeight = 'editor_remove_weight';
const EditorEventSavePage = 'editor_save_page';
const EditorEventGetPage = 'editor_get_page';
const EditorEventPageList = 'editor_get_page_list';
const EditorEventWeightTree = 'editor_get_page_weight';

const EditorEventWindowResize = 'editor_Window_resize';
const EditorEventResize = 'editor_resize';
const EditorEventOuterWidthChange = 'editor_outer_width_change'; // 外部
const EditorEventViewInit = 'editor_view_init'; // 只能获取所需的元素，请不要获取其他尺寸信息
const EditorEventGetStyleSuccess = 'editor_get_style_success';
const EditorEventAfterViewInit = 'editor_after_view_init';
const EditorEventBrowserResize = 'editor_browser_resize';
const EditorEventScroll = 'editor_scroll';
const EditorEventPositionChange = 'editor_position_change';
const EditorEventDragStart = 'editor_drag_start';
const EditorEventBrowserReady = 'editor_browser_ready';
const EditorEventOpenEditDialog = 'editor_open_edit_dialog';
const EditorEventOpenProperty = 'editor_open_property';
const EditorEventDrag = 'editor_drag'; // 拖拽开始
const EditorEventDrog = 'editor_drog'; // 拖拽放下
const EditorEventOperateWeight = 'editor_operate_weight';
const EditorEventMouseMove = 'editor_mouse_move';
const EditorEventMouseUp = 'editor_mouse_up';
const EditorEventTimeLoop = 'editor_time_loop';
const EditorMobileStyle = 'mobile-style';
const EditorEventInputChange = 'value:change';
const EditorEventInputReset = 'value:reset';
const EditorEventTabToggle = 'tab:toggle';
const EditorEventListEdit = 'list:edit';
const EditorEventFlipToggle = 'flip:toggle';
const EditorEventFlipFinish = 'flip:finish';

type FailureCallbackFunc = (message: string, code?: number) => void;

interface EditorListeners {
    [EditorEventGetWeights]: (success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventGetPage]: (success: (data: IPageModel) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventPageList]: (success: (data: IPageModel[]) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventWeightTree]: (success: (data: IPageWeight[]) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventWindowResize]: (width: number, height: number) => void;
    [EditorEventResize]: (width: number, height: number) => void;
    [EditorEventSavePage]: (data: any[], success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventMouseMove]: (p: IPoint) => void;
    [EditorEventMouseUp]: (p: IPoint) => void;
    [EditorEventTimeLoop]: (now: Date) => void;
    [EditorEventOuterWidthChange]: (width: number) => void;
    [EditorEventViewInit]: () => void;
    [EditorEventAfterViewInit]: () => void;
    [EditorEventDrog]: (...items: JQuery<HTMLDivElement>[]) => void;
    [EditorEventOperateWeight]: (isNew: boolean, target: JQuery<HTMLDivElement>, row: JQuery<HTMLDivElement>, replace?: JQuery<HTMLDivElement>) => void;
    [EditorEventGetWeightProperty]: (id: number, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventGetStyleSuccess]: (data: any) => void;
    [EditorEventSaveWeightProperty]: (id: number, data: Object, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventDrag]: (ele: JQuery<HTMLDivElement>, isNew: boolean, p: IPoint) => void;
    [EditorEventDragStart]: (ele: JQuery<HTMLDivElement>) => void;
    [EditorEventOpenProperty]: (target: EditorWeight) => void;
    [EditorEventWeightForm]: (id: number, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventPositionChange]: (left: number, top: number, scale: number) => void;
    [EditorEventBrowserReady]: (doc: JQuery<Document>) => void;
    [EditorEventBrowserResize]: (left: number, top: number, width: number, height: number, maxWidth: number, maxHeight: number) => void;
    [EditorEventRefreshWeight]: (id: number, success: (data: any) => void, failure: FailureCallbackFunc) => void;
    [EditorEventOpenEditDialog]: (target: EditorWeight) => void;
    [EditorEventRemoveWeight]: (id: number, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventMoveWeight]: (data: any, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventAddWeight]: (data: any, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventScroll]: (offset: number, horizontal: boolean) => void;
}

interface IRuleLine {
    value: number;
    label: string|number;
    horizontal?: boolean;
}


interface IPoint {
    x: number;
    y: number;
}

interface ISize {
    width: number;
    height: number;
}

interface IBound extends ISize {
    left: number;
    top: number;
}

interface IEditorPanel {
    render(): JQuery;
    show(): void;
    hide(): void; 
}

interface IPageModel {
    id: string;
    name: string;
    title: string;
    edit_url: string;
    editable_url: string;
    preview_url: string;
}


interface IPageWeight {
    id: string;
    title: string;
    children: IPageWeight[];
}

interface IItem {
    name: string;
    value: any;
}

interface IRgbaColor {
    r: number; // 255
    g: number; // 255
    b: number; // 255
    a: number; // 1
}

interface IHslColor {
    h: number; // 360
    s: number; // 100
    l: number; // 100
    a: number; // 1
}

interface IVisualInput {
    [key: string]: any;
    type: string;
    name: string;
    label: string;
    value?: any;
    items?: any[];
    input?: IVisualInput[];
    class?: string;
    hidden?: boolean;
    option?: {
        [key: string]: any;
        'data-tab': string;
        tab?: string;
    };
}

class VisualEditor {

    private listeners: {
        [key: string]: Function[];
    } = {};
    private mouseListener = {
        move: undefined,
        finish: undefined,
    };
    private hScrollBar = new EditorScrollBar(this, true);
    private vScrollBar = new EditorScrollBar(this, false);
    private toolBar = new EditorToolBar(this);
    private hRuleBar = new EditorRuler(this, true);
    private vRuleBar = new EditorRuler(this, false);
    // private ruleLinePanel: JQuery<HTMLDivElement>;
    public browser = new EditorBrowser(this);
    private workspace: JQuery<HTMLDivElement>;
    private panelGroup = new EditorPanelGroup(this);
    private weightSoul = new EditorWeightSoul(this);
    private dialog = new EditorDialog(this);

    private viewInited = false; // 页面是否加载完成
    private browserAdaptive = false; // 是否启动自适应


    constructor(
        private box: JQuery<HTMLDivElement>
    ) {
        
    }

    public set outerWidth(arg: number) {
        this.box.width(arg);
    }

    public get outerWidth(): number {
        return this.box.width();
    }

    public set outerHeight(arg: number) {
        this.box.height(arg);
    }

    public get outerHeight(): number {
        return this.box.height();
    }

    // 工作区间的尺寸
    public get innerWidth(): number {
        if (!this.viewInited) {
            return 0;
        }
        return this.workspace.width() - this.vScrollBar.barSize - this.vRuleBar.barSize;
    }

    public get innerHeight(): number {
        if (!this.viewInited) {
            return 0;
        }
        return this.workspace.height() - this.hRuleBar.barSize - this.hScrollBar.barSize;
    }

    public on<E extends keyof EditorListeners>(event: E, listener: EditorListeners[E]): this;
    public on(event: string, cb?: Function) {
        if (!Object.prototype.hasOwnProperty.call(this.listeners, event)) {
            this.listeners[event] = [];
        }
        this.listeners[event].push(cb);
        return this;
    }

    public emit<E extends keyof EditorListeners>(event: E, ...eventObject: Parameters<EditorListeners[E]>): this;
    public emit(event: string, ...items: any[]) {
        if (!Object.prototype.hasOwnProperty.call(this.listeners, event)) {
            return this;
        }
        const listeners = this.listeners[event];
        for (let i = listeners.length - 1; i >= 0; i--) {
            const cb = listeners[i];
            const res = cb(...items);
            //  允许事件不进行传递
            if (res === false) {
                break;
            }
        }
        return this;
    }

    public get hasMouseListener() {
        return typeof this.mouseListener.move !== 'undefined';
    }

    /**
     * 鼠标移动事件监听
     * @param move 
     * @param finish 
     */
    public onMouse(move?: (p: IPoint) => void, finish?: (p: IPoint) => void) {
        this.mouseListener = {
            move,
            finish: !move && !finish ? undefined : (p: IPoint) => {
                this.mouseListener = {move: undefined, finish: undefined};
                finish && finish(p);
            },
        };
    }
 

    /**
     * 切换为手机模式
     * @param width 
     * @param height 
     */
    public mobile(width: number, height: number) {
        this.browserAdaptive = false;
        this.browser.toggleShell(true);
        this.resize(width, height);
    }

    /**
     * 切换为普通模式
     */
    public normal();
    /**
     * 切换为普通模式
     * @param width 
     * @param height 
     */
    public normal(width: number, height: number);
    public normal(width?: number, height?: number) {
        this.browser.toggleShell(false);
        if (width && height) {
            this.browserAdaptive = false;
            this.resize(width, height);
            return;
        }
        this.browserAdaptive = true;
        this.resize(this.innerWidth, this.innerHeight);
    }

    /**
     * 修改视图尺寸
     * @param width 
     * @param height 
     */
    public resize(width: number, height: number) {
        this.browser.resize(width, height);
    }

    /**
     * 重置滚动和缩放比例
     */
    public reset() {
        this.browser.reset();
    }

    public reload() {
        this.browser.reload();
    }

    /**
     * 获取页面布局
     */
    public serialize() {
        return this.browser.serialize();
    }

    /**
     * 反转browser的高和宽
     */
    public rotate() {
        this.browser.rotate();
    }

    /**
     * 缩放比例
     * @param scale /100
     */
    public scale(scale: number) {
        this.browser.scale(scale);
    }

    public find<T>(name: string): JQuery<T> {
        return this.box.find(name) as any;
    }

    public run() {
        this.viewInited = false;
        this.bindEvent();
        this.viewInit();
        this.emit(EditorEventGetPage, (data: IPageModel) => {
            this.browser.navigate(data.editable_url);
        }, message => {

        });
    }

    private bindEvent() {
        const $window = $(window);
        $window.on('resize', () => {
            this.emit(EditorEventWindowResize, $window.width(), $window.height());
        });
        const bindDocEvent = (doc: JQuery<Document>, isBrowser = false) => {
            doc.on('keydown', e => {
                if (e.ctrlKey) {
                    if (e.code == 'KeyS') {
                        e.preventDefault();
                        this.emit(EditorEventSavePage, this.serialize(), () => {});
                    }
                }
            }).on('paste', (e: any) => {
                if (e.clipboardData || e.originalEvent) {
                    const clipboardData = (e.clipboardData || (window as any).clipboardData);
                    const val = clipboardData?.getData('text');
                }
            }).on('mousemove', e => {
                const p = {x: e.clientX, y: e.clientY};
                this.emit(EditorEventMouseMove, isBrowser ? this.browser.globePosition(p) : p);
            }).on('mouseup', e => {
                const p = {x: e.clientX, y: e.clientY};
                this.emit(EditorEventMouseUp, isBrowser ? this.browser.globePosition(p) : p);
            }).on('touchmove', e => {
                const p = {x: e.touches[0].clientX, y: e.touches[0].clientY};
                this.emit(EditorEventMouseMove, isBrowser ? this.browser.globePosition(p) : p);
            }).on('touchend', e => {
                const p = {x: e.changedTouches[0].clientX, y: e.changedTouches[0].clientY};
                this.emit(EditorEventMouseUp, isBrowser ? this.browser.globePosition(p) : p);
            });
        };
        this.on(EditorEventResize, (width: number, height: number) => {
            if (this.browserAdaptive) {
                this.resize(width, height);
            }
        }).on(EditorEventOuterWidthChange, () => {
            if (!this.viewInited) {
                return;
            }
            this.emit(EditorEventResize, this.innerWidth, this.innerHeight);
        }).on(EditorEventAfterViewInit, () => {
            this.emit(EditorEventResize, this.innerWidth, this.innerHeight);
            this.normal();
        }).on(EditorEventWindowResize, (ww: number, wh: number) => {
            const top = this.box.offset().top;
            const height = wh - top - (this.box.closest('.only-editor').length > 0 ? 2 : 20);
            this.box.toggleClass('visual-mobile-editor', ww < 780 && ww < wh);
            this.outerHeight = height;
            if (!this.viewInited) {
                return;
            }
            this.emit(EditorEventResize, this.innerWidth, this.innerHeight);
        }).on(EditorEventMouseMove, p => {
            if (this.mouseListener.move) {
                this.mouseListener.move(p);
            }
        }).on(EditorEventMouseUp, p => {
            if (this.mouseListener.finish) {
                this.mouseListener.finish(p);
            }
        }).on(EditorEventBrowserReady, (doc: JQuery<Document>) => {
            doc.find('body').addClass('visual-editor-editable');
            bindDocEvent(doc, true);
        });
        bindDocEvent($(document));
        this.emit(EditorEventWindowResize, $window.width(), $window.height());
        this.loopCheckBox();
    }

    /**
     * 循环检测box的宽度变化
     */
    private loopCheckBox() {
        let lastBoxWidth = this.box.width();
        const space = 500;
        const checkFunc = () => {
            const now = new Date();
            this.emit(EditorEventTimeLoop, now);
            const w = this.box.width();
            if (lastBoxWidth !== w) {
                lastBoxWidth = w;
                this.emit(EditorEventOuterWidthChange, w);
            }
            setTimeout(checkFunc, space);
        };
        setTimeout(checkFunc, space);
    }

    private viewInit() {
        this.box.addClass('visual-editor');
        this.box.html(`
        <div class="panel-group"></div>
        <div class="editor-container">
            <div class="work-container">
                <div class="shell-box">
                    <div class="shell-bar">
                        <div>
                            <i class="fa fa-ellipsis-h"></i>
                            <i class="fa fa-signal"></i>
                            <i class="fa fa-wifi"></i>
                        </div>
                        <div class="time">
                        </div>
                        <div class="pull-right">
                            100%
                            <i class="fa fa-battery-full"></i>
                        </div>
                    </div>
                </div>
                <iframe class="panel-main"></iframe>
            </div>
            <div class="rule-box">
                <canvas class="h-rule-bar"></canvas>
                <canvas class="v-rule-bar"></canvas>
                <div class="rule-line-bar"></div>
            </div>
            <div class="h-scroll-bar">
                <div class="inner-bar"></div>
            </div>
            <div class="v-scroll-bar">
                <div class="inner-bar"></div>
            </div>
            <div class="panel-tool-bar">
                <i class="fa fa-minus-circle" title="缩小"></i>
                <i class="scale-text">100%</i>
                <i class="fa fa-plus-circle" title="放大"></i>
                <i class="fa fa-expand-arrows-alt" title="重置"></i>
                <i class="fa fa-expand" title="全屏"></i>
                <i class="fa fa-undo" title="翻转"></i>
            </div>
        </div>
        <div class="await-widget-box">
        </div>
        <div class="dialog dialog-box editor-dialog" data-type="dialog" >
            <div class="dialog-header">
                <div class="dialog-title">编辑</div>
                <i class="fa fa-close dialog-close"></i>
            </div>
            <form class="dialog-body control-dialog-body">
                
            </form>
            <div class="dialog-footer">
                <button type="button" class="dialog-yes">确认</button>
                <button type="button" class="dialog-close">取消</button>
            </div>
        </div>
        `);
        this.emit(EditorEventViewInit);
        this.workspace = this.find<HTMLDivElement>('.editor-container');
        // this.ruleLinePanel = this.find<HTMLDivElement>('.rule-line-bar');
        this.viewInited = true;
        this.emit(EditorEventAfterViewInit);
    }
}

class EditorToolBar {

    private scaleBar: JQuery<HTMLSpanElement>;
    private scaleValue = 100;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventViewInit, () => {
            const box = this.editor.find<HTMLDivElement>('.panel-tool-bar');
            this.scaleBar = box.find<HTMLSpanElement>('.scale-text');
            this.bindEvent(box);
        }).on(EditorEventPositionChange, (left: number, top: number, scale: number) => {
            this.scaleValue = scale;
            this.scaleBar.text(scale + '%');
        });
    }

    private bindEvent(box: JQuery<HTMLDivElement>) {
        box.on('click', '.fa-minus-circle', () => {
            if (this.scaleValue < 30) {
                return;
            }
            this.editor.scale(this.scaleValue - 10);
        }).on('click', '.fa-plus-circle', () => {
            if (this.scaleValue > 300) {
                return;
            }
            this.editor.scale(this.scaleValue + 10);
        }).on('click', '.fa-undo', () => {
            this.editor.rotate();
        }).on('click', '.fa-expand', () => {
            this.editor.normal();
        }).on('click', '.fa-expand-arrows-alt', () => {
            this.editor.reset();
        });
    }
}

class EditorWeightSoul {
    private box: JQuery<HTMLDivElement>;
    private target: JQuery<HTMLDivElement>;
    private isNew = false;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventViewInit, () => {
            this.box = this.editor.find<HTMLDivElement>('.await-widget-box');
        }).on(EditorEventDrag, (element: JQuery<HTMLDivElement>, isNew: boolean, mousePos: IPoint) => {
            const offset = element.offset();
            const mouseInPos = {
                x: mousePos.x - offset.left,
                y: mousePos.y - offset.top
            };
            const width = element.width();
            const height = element.height();
            this.target = element;
            this.isNew = isNew;
            this.box.html(element.prop('outerHTML'));
            this.editor.onMouse(p => {
                if (!this.target) {
                    return;
                }
                this.setBound(p.x - mouseInPos.x, p.y - mouseInPos.y, width, height);
            }, p => {
                if (!this.target) {
                    return;
                }
                const items = this.editor.browser.getElementByPoint(p);
                if (!items || !items[0]) {
                    this.reset();
                    return;
                }
                // 在手机端 touchend 事件出现问题无法被部件捕捉到
                this.editor.emit(EditorEventDrog, ...items);
            });
        }).on(EditorEventDrog, (row: JQuery<HTMLDivElement>, replace?: JQuery<HTMLDivElement>) => {
            if (!this.target) {
                return;
            }
            this.editor.emit(EditorEventOperateWeight, this.isNew, this.target, row, replace);
            this.reset();
        });
    }

    private setBound(x: number, y: number, width: number, height: number) {
        this.box.css({
            width: width + 'px',
            height: height + 'px',
            left: x + 'px',
            top: y + 'px'
        });
    }

    private reset() {
        if (!this.target) {
            return;
        }
        this.target = undefined;
        this.setBound(0, 0, 0, 0);
    }
}

class EditorPanelGroup {

    private children: IEditorPanel[] = [
        new EditorWeightPanel(this.editor),
        new EditorPropertyPanel(this.editor),
        new EditorLayerPanel(this.editor),
        new EditorPagePanel(this.editor),
        new EditorSettingPanel(this.editor)
    ];
    private renderedChildren: {
        target: JQuery,
        control: IEditorPanel,
    }[] = [];
    private box: JQuery<HTMLDivElement>;
    private visible = true;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventViewInit, () => {
            this.box = this.editor.find<HTMLDivElement>('.panel-group');
            for (const item of this.children) {
                this.onRender(item);
            }
        }).on(EditorEventAfterViewInit, () => {
            this.bindEvent();
        }).on(EditorEventResize, () => {
            const maxHeight = this.box.height();
            for (const item of this.renderedChildren) {
                item.target.find('.panel-body').height(maxHeight - 36);
            }
        });
    }

    private bindEvent() {
        const that = this;
        this.box.on('click', '.panel-item .panel-header .fa-close', function(e) {
            e.stopPropagation();
            const target = $(this).closest('.panel-item');
            for (const item of that.renderedChildren) {
                if (that.isSame(item.target, target)) {
                    item.control.hide();
                }
            }
            that.toggle(false);
        }).on('click', '.panel-header', function() {
            const target = $(this).closest('.panel-item');
            if (!target.hasClass('min')) {
                return;
            }
            for (const item of that.renderedChildren) {
                if (that.isSame(item.target, target)) {
                    item.control.show();
                } else {
                    item.control.hide();
                }
            }
            that.toggle(true);
        }).on('click', '.expand-box .expand-header', function() {
            $(this).closest('.expand-box').toggleClass('open');
        });
    }

    public toggle(visible: boolean) {
        if (this.visible === visible) {
            return;
        }
        this.box.toggleClass('min', !visible);
        this.visible = visible;
        this.editor.emit(EditorEventResize, this.editor.innerWidth, this.editor.innerHeight);
    }

    public add(child: IEditorPanel) {
        this.children.push(child);
        this.onRender(child);
    }

    private onRender(child: IEditorPanel) {
        if (!this.box) {
            return;
        }
        this.children.indexOf(child);
        const target = child.render();
        this.box.append(target);
        target.addClass('panel-item');
        if (this.renderedChildren.length > 0) {
            child.hide();
        } else {
            child.show();
        }
        this.renderedChildren.push({
            target,
            control: child
        });
    }

    private isSame(ele: JQuery, dist: JQuery): boolean {
        return ele.is(dist);
    }

}

class EditorPropertyPanel implements IEditorPanel {

    private box: JQuery<HTMLDivElement>;
    private target: EditorWeight;
    private lastAt: number = 0;
    private isAsync = false;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventAfterViewInit, () => {
            this.bindEvent();
        }).on(EditorEventGetStyleSuccess, data => {
            this.renderStyle(data);
        }).on(EditorEventOpenProperty, (weight: EditorWeight) => {
            this.saveSync();
            this.box.find<HTMLDivElement>('.panel-header').trigger('click');
            this.target = weight;
            this.editor.emit(EditorEventGetWeightProperty, weight.id(), data => {
                this.editor.emit(EditorEventGetStyleSuccess, data.styles);
                this.lastAt = new Date().getTime();
                this.applyForm(data);
            });
        }).on(EditorEventTimeLoop, (now: Date) => {
            if (!this.isAsync) {
                return;
            }
            const space = 20000;
            const nowTime = now.getTime();
            if (nowTime - this.lastAt > space) {
                this.saveSync();
                this.lastAt = nowTime;
            }
        });
    }

    private bindEvent() {
        if (!this.box) {
            return;
        }
        const that = this;
        EditorHtmlHelper.bindInputEvent(this.box);
        this.box.on(EditorEventInputChange, '.control-inline-group,.control-line-group', function() {
            that.autoSave();
        }).on('click', '.style-item', function() {
            $(this).addClass('active').siblings().removeClass('active');
            that.autoSave();
        }).on('click', '.reset-btn', () => {
            this.applyForm({});
            this.autoSave();
        });
    }

    private saveSync() {
        this.isAsync = false;
        if (!this.target) {
            return;
        }
        const data: any = EditorHtmlHelper.formData(this.box.find('.form-table'));
        data.style_id = EditorHelper.parseNumber(this.box.find('.style-item.active').attr('data-id'));
        data.id = this.target.id();
        this.editor.emit(EditorEventSaveWeightProperty, this.target.id(), data as Object, data => {
            this.target.html(data.html);
        });
    }

    private autoSave() {
        this.isAsync = true;
        this.lastAt = new Date().getTime();
    }

    public render(): JQuery {
        this.box = $(`<div class="panel-item min" data-panel="property"></div`);
        this.box.html(`
            <div class="panel-header">
                <span class="title">属性</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="panel-body">
            <div class="tab-box">
                    <div class="tab-header"><div class="tab-item active">
                            普通
                        </div><div class="tab-item">
                            高级
                        </div><div class="tab-item">
                            样式
                        </div></div>
                    <div class="tab-body">
                        <div class="tab-item active">
                        </div>
                        <div class="tab-item">
                        <div class="expand-box open">
                                <div class="expand-header">整体<span class="fa fa-chevron-right"></span></div>
                                <div class="expand-body">
                                    
                                </div>
                            </div>
                            <div class="expand-box">
                                <div class="expand-header">标题<span class="fa fa-chevron-right"></span></div>
                                <div class="expand-body">
                                    
                                </div>
                            </div>
                            <div class="expand-box">
                                <div class="expand-header">内容<span class="fa fa-chevron-right"></span></div>
                                <div class="expand-body">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-item style-panel">
                            <div class="style-item" data-id="0">
                                无
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
        return this.box;
    }

    private renderStyle(items: any[]) {
        const html = EditorHtmlHelper.mapJoinHtml(items, item => {
            return `<div class="style-item" data-id="${item.id}">
            <img src="${item.thumb}" alt="${item.name}">
        </div>`;
        });
        this.box.find('.style-panel').html(`<div class="style-item" data-id="0">无</div>${html}`);
    }

    public show() {
        this.box.removeClass('min');
    }
    public hide() {
        this.box.addClass('min')
    }

    private applyForm(data: any) {
        // const styles = data.settings?.style;
        let items = this.box.find('.tab-body .tab-item');
        items[0].innerHTML = EditorHtmlHelper.render(data.form.basic)
                        + EditorHtmlHelper.buttonGroup(EditorHtmlHelper.button('重置', 'reset-btn'));
        items[1].innerHTML = EditorHtmlHelper.render(data.form.style);
        this.box.find('.style-item').each(function() {
            let $this = $(this);
            $this.toggleClass('active', $this.attr('data-id') == data.style_id);
        });
    }
}

class EditorWeightPanel implements IEditorPanel {
    private box: JQuery<HTMLDivElement>;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventAfterViewInit, () => {
            this.editor.emit(EditorEventGetWeights, data => {
                this.renderWeight(data.weights);
                // this.editor.emit(EditorEventGetStyleSuccess, data.styles);
                // this.box.find('.visual-edit-control').attr('draggable', 'true');
            });
            this.bindEvent();
        });
    }

    private bindEvent() {
        const that = this.editor;
        this.box
        .on('mousedown', '.visual-edit-control', function(e) {
            const $this = $(this);
            if ($this.attr('draggable') === 'true') {
                return;
            }
            e.stopPropagation();
            e.preventDefault();
            that.emit(EditorEventDrag, $this, true, {
                x: e.clientX,
                y: e.clientY,
            });
        }).on('touchstart', '.visual-edit-control', function(e) {
            const $this = $(this);
            e.stopPropagation();
            e.preventDefault();
            that.emit(EditorEventDrag, $this, true, {
                x: e.touches[0].clientX,
                y: e.touches[0].clientY,
            });
        }).on('dragstart', '.visual-edit-control', function(e) {
            e.originalEvent.dataTransfer.setData("Text", e.target.id);
            that.emit(EditorEventDragStart, $(this));
        });
    }

    public render(): JQuery {
        this.box = $(`<div class="panel-item" data-panel="weight"></div`);
        this.box.html(`
            <div class="panel-header">
                <span class="title">部件</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="panel-body">
            </div>
        `);
        return this.box;
    }

    public renderWeight(data: any) {
        const html = EditorHtmlHelper.mapJoinHtml(data, group => {
            const text = EditorHtmlHelper.mapJoinHtml(group.items, item => {
                if (!item) {
                    return '';
                } 
                const editable = item.editable ? '<a class="edit">编辑</a>' : '';
                return `<div class="visual-edit-control" data-type="weight" data-weight="${item.id}" data-group="${group.id}">
                <div class="visual-preview">
                    <div class="thumb">
                        <img src="${item.thumb}" alt="${item.name}" title="${item.description}">
                    </div>
                    <p class="title">${item.name}</p>
                </div>
                <div class="visual-action">
                    <a class="refresh">刷新</a>
                    ${editable}
                    <a class="property">属性</a>
                    <a class="drag">拖拽</a>
                    <a class="del">删除</a>
                </div>
                <div class="visual-view">
                    <img src="/assets/images/ajax.gif" alt="">
                </div>
            </div>`;
            });
            return `
            <ul class="menu">
                <li class="expand-box open">
                    <div class="expand-header">
                        ${group.name}
                        <span class="fa fa-chevron-right"></span>
                    </div>
                    <div class="expand-body list-view">
                    ${text}
                    </div>
                    </li>
                </ul>
            `;
        });
        this.box.find('.panel-body').html(html);
    }

    public show() {
        this.box.removeClass('min');
    }
    public hide() {
        this.box.addClass('min')
    } 
}

class EditorLayerPanel implements IEditorPanel {
    private box: JQuery<HTMLDivElement>;
    private booted = false;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventAfterViewInit, () => {
            this.bindEvent();
        });
    }

    private bindEvent() {
        const that = this.editor;
        this.box.on('click', '.tree-item .item-open-icon', function() {
            $(this).closest('.tree-item').toggleClass('open');
        });
    }

    public render(): JQuery {
        this.box = $(`<div class="panel-item" data-panel="layer"></div`);
        this.box.html(`
            <div class="panel-header">
                <span class="title">组件层</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="panel-body tree-container">
            </div>
        `);
        return this.box;
    }

    public show() {
        this.box.removeClass('min');
        if (this.booted) {
            return;
        }
        this.booted = true;
        this.editor.emit(EditorEventWeightTree, data => {
            this.box.find('.panel-body').html(this.renderItems(data));
        });
    }
    public hide() {
        this.box.addClass('min')
    }

    private renderItems(items: any): string {
        let html = '';
        EditorHelper.eachObject(items, item => {
            let children = '';
            let iconArrow = '';
            if (item.children && item.children.length > 0) {
                children = `<div class="item-children">${this.renderItems(items)}</div>`;
                iconArrow = `<div class="item-open-icon">
                <i class="fa fa-chevron-right"></i>
            </div>`
            }
            const name = item.name ?? item.id;
            html += `<div class="tree-item">
            <div class="item-body">
                ${iconArrow}
                <div class="item-icon">
                    <i class="fa fa-chain"></i>
                </div>
                <input type="text" class="item-title" value="${name}">
                <div class="item-action-icon">
                    <i class="fa fa-ellipsis-h"></i>
                    <div class="item-action-bar">
                        <i class="fa fa-trash"></i>
                        <i class="fa fa-copy"></i>
                        <i class="fa fa-plus"></i>
                        <i class="fa fa-edit"></i>
                    </div>
                </div>
            </div>
            ${children}
        </div>`;
        });
        return html;
    }
}

class EditorPagePanel implements IEditorPanel {
    private box: JQuery<HTMLDivElement>;
    private booted = false;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventAfterViewInit, () => {
            this.bindEvent();
        });
    }

    private bindEvent() {
        const that = this.editor;
        this.box.on('click', '.list-item .item-title', function() {
            $(this).closest('.list-item').addClass('active').siblings().removeClass('active');
        });
    }

    public render(): JQuery {
        this.box = $(`<div class="panel-item" data-panel="layer"></div`);
        this.box.html(`
            <div class="panel-header">
                <span class="title">页面</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="panel-body list-container">
            </div>
        `);
        return this.box;
    }

    public show() {
        this.box.removeClass('min');
        if (this.booted) {
            return;
        }
        this.booted = true;
        this.editor.emit(EditorEventPageList, data => {
            this.box.find('.panel-body').html(data.map(this.renderItem.bind(this)).join(''));
        });
    }

    public hide() {
        this.box.addClass('min')
    }


    private renderItem(data: any) {
        return `<div class="list-item">
        <span class="item-title">${data.title}</span>
        <div class="item-action-icon">
            <i class="fa fa-ellipsis-h"></i>
            <div class="item-action-bar">
                <i class="fa fa-trash"></i>
                <i class="fa fa-copy"></i>
                <i class="fa fa-edit"></i>
            </div>
        </div>
    </div>`;
    }

}

class EditorSettingPanel implements IEditorPanel {
    private box: JQuery<HTMLDivElement>;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventAfterViewInit, () => {
            this.bindEvent();
        });
    }

    private bindEvent() {
        const that = this.editor;
        this.box.on('click', '.tree-item .item-open-icon', function() {
            $(this).closest('.tree-item').toggleClass('open');
        });
    }

    public render(): JQuery {
        this.box = $(`<div class="panel-item" data-panel="layer"></div`);
        this.box.html(`
            <div class="panel-header">
                <span class="title">配置</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="panel-body">
                <div class="tab-box">
                    <div class="tab-header"><div class="tab-item active">
                            页面配置
                        </div><div class="tab-item">
                            主题配置
                        </div></div>
                    <div class="tab-body">
                        <div class="tab-item active">
                        </div>
                        <div class="tab-item">
                        </div>
                    </div>
                </div>
            </div>
        `);
        return this.box;
    }

    public show() {
        this.box.removeClass('min');
    }
    public hide() {
        this.box.addClass('min')
    }

}

class EditorDialog {

    private box: DialogBox;
    private target: EditorWeight;
    private innerForm: JQuery<HTMLFormElement>;

    constructor(
        private editor: VisualEditor
    ) {
        const that = this;
        this.editor.on(EditorEventViewInit, () => {
            const target = this.editor.find<HTMLDivElement>('.editor-dialog');
            this.innerForm = target.find<HTMLFormElement>('.dialog-body');
            this.box = (target as any).dialog();
            this.bindEvent();
            this.box.on('done', () => {
                this.editor.emit(EditorEventSaveWeightProperty, this.target.id(), EditorHtmlHelper.formData(target.find('.dialog-body')), data => {
                    that.editor.emit(EditorEventOpenProperty, that.target);
                    that.target.html(data.html);
                });
                this.box.close();
            })
        }).on(EditorEventOpenEditDialog, (weight: EditorWeight) => {
            this.target = weight;
            this.editor.emit(EditorEventWeightForm, weight.id(), data => {
                this.box.find('.dialog-body').html(`<input type="hidden" name="id" value="${weight.id()}">${EditorHtmlHelper.render(data.form)}`);
                this.box.showCenter();
            });
        });
    }

    private bindEvent() {
        EditorHtmlHelper.bindInputEvent(this.box.box as any);
    }

}


class EditorBrowser {
    private shell: JQuery<HTMLDivElement>;
    private shellTimeBar: JQuery<HTMLSpanElement>;
    private frame: JQuery<HTMLIFrameElement>;
    private frameBody: JQuery<HTMLBodyElement>;
    private dragWeight: JQuery<HTMLDivElement>;
    private selectedWeight: EditorWeight;
    private bound: IBound;
    private frameScale = 100;
    private shellVisbile = false;

    constructor(
        private editor: VisualEditor,
    ) {
        this.editor.on(EditorEventViewInit, () => {
            this.shellTimeBar = this.editor.find<HTMLDivElement>('.shell-bar .time');
            this.shell = this.editor.find<HTMLDivElement>('.work-container');
            this.frame = this.editor.find<HTMLIFrameElement>('.panel-main');
            this.bindEvent();
        }).on(EditorEventScroll, (offset: number, horizontal: boolean) => {
            if (horizontal) {
                this.bound.left = -offset;
            } else {
                this.bound.top = -offset;
            }
            this.renderTransform();
            this.editor.emit(EditorEventPositionChange, this.bound.left, this.bound.top, this.frameScale);
        }).on(EditorEventResize, () => {
            if (!this.bound) {
                return;
            }
            this.resize(this.bound.width, this.bound.height);
        }).on(EditorEventDragStart, (weight: JQuery<HTMLDivElement>) => {
            this.dragWeight = weight;
        }).on(EditorEventTimeLoop, (now: Date) => {
            if (!this.shellVisbile) {
                return;
            }
            this.shellTimeBar.text([now.getHours(), now.getMinutes()].map(EditorHelper.twoPad).join(':'));
        }).on(EditorEventOperateWeight, (isNew: boolean, weight: JQuery<HTMLDivElement>, row: JQuery<HTMLDivElement>, replace?: JQuery<HTMLDivElement>) => {
            this.dragWeight = undefined;
            if (isNew) {
                this.selectedWeight = new EditorWeight(this.editor, weight.clone());
                this.selectedWeight.appendTo(row, replace);
                return;
            }
            this.selectedWeight = new EditorWeight(this.editor, weight);
            this.selectedWeight.moveTo(row, replace);
        });
    }

    private bindEvent() {
        this.frame.on('load', () => {
            const $doc = this.frame.contents();
            this.frameBody = $doc.find<HTMLBodyElement>('body');
            this.editor.emit(EditorEventBrowserReady, $doc as any);
            this.bindFrameEvent();
        });
    }

    private bindFrameEvent() {
        const that = this;
        const drogFunc = (row: JQuery<HTMLDivElement>, replace?: JQuery<HTMLDivElement>) => {
            if (that.dragWeight) {
                that.selectedWeight = new EditorWeight(that.editor, that.dragWeight.clone());
                that.selectedWeight.appendTo(row, replace);
                return;
            }
            if (that.selectedWeight) {
                that.selectedWeight.moveTo(row, replace);
            }
        };
        this.frameBody.on('dragover', '.visual-edit-row', (e) => {
            e.stopPropagation();
            e.preventDefault();
        }).on('drop', '.visual-edit-row', function(e) {
            e.stopPropagation();
            e.preventDefault();
            drogFunc($(this));
        }).on('mouseup', '.visual-edit-row', function(e) {
            e.stopPropagation();
            e.preventDefault();
            that.editor.emit(EditorEventDrog, $(this));
        }).on('mouseup', '.visual-edit-control', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const row = $(this);
            that.editor.emit(EditorEventDrog, row.closest('.visual-edit-row'), row);
        }).on('touchend', '.visual-edit-row', function(e) {
            e.stopPropagation();
            e.preventDefault();
            that.editor.emit(EditorEventDrog, $(this));
        }).on('touchend', '.visual-edit-control', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const row = $(this);
            that.editor.emit(EditorEventDrog, row.closest('.visual-edit-row'), row);
        }).on('dragover', '.visual-edit-control', (e) => {
            e.stopPropagation();
            e.preventDefault();
        }).on('drop', '.visual-edit-control', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const row = $(this);
            drogFunc(row.closest('.visual-edit-row'), row);
        }).on('dragstart', '.visual-edit-control', function(e) {
            e.originalEvent.dataTransfer.setData("Text", e.target.id);
            that.selectedWeight = new EditorWeight(that.editor, $(this));
        }).on('dragend', '.visual-edit-control', function() {
            $(this).attr('draggable', 'false');
        }).on('mousedown', '.visual-action .drag', function(e) {
            e.stopPropagation();
            that.dragWeight = undefined;
            const weight = $(this).closest('.visual-edit-control');
            that.editor.emit(EditorEventDrag, weight, false, {
                x: e.clientX,
                y: e.clientY
            });
            // that.selectedWeight = new EditorWeight(that.editor, weight);
            // $(this).attr('draggable', 'true');
        }).on('touchstart', '.visual-action .drag', function(e) {
            e.stopPropagation();
            e.preventDefault();
            that.dragWeight = undefined;
            const weight = $(this).closest('.visual-edit-control');
            that.editor.emit(EditorEventDrag, weight, false, {
                x: e.touches[0].clientX,
                y: e.touches[0].clientY
            });
        }).on('click', '.visual-action .del', function(e) {
            e.stopPropagation();
            that.selectedWeight = new EditorWeight(that.editor, $(this).closest('.visual-edit-control'));
            that.selectedWeight.tapRemove();
        }).on('click', '.visual-action .edit', function(e) {
            e.stopPropagation();
            that.selectedWeight = new EditorWeight(that.editor, $(this).closest('.visual-edit-control'));
            that.selectedWeight.tapEdit();
        }).on('click', '.visual-action .refresh', function(e) {
            e.stopPropagation();
            that.selectedWeight = new EditorWeight(that.editor, $(this).closest('.visual-edit-control'));
            that.selectedWeight.tapRefresh();
        }).on('click', '.visual-action .property', function(e) {
            e.stopPropagation();
            that.selectedWeight = new EditorWeight(that.editor, $(this).closest('.visual-edit-control'));
            that.selectedWeight.tapProperty();
        }).on('click', 'a', function(e) {
            e.preventDefault();
        });
    }

    public globePosition(p: IPoint): IPoint {
        const offset = this.frame.offset();
        return {
            x: p.x + offset.left,
            y: p.y + offset.top
        }
    }

    public framePosition(p: IPoint): IPoint {
        const offset = this.frame.offset();
        return {
            x: p.x - offset.left,
            y: p.y - offset.top
        }
    }

    public getElementByPoint(p: IPoint): JQuery<HTMLDivElement>[] {
        const pos = this.framePosition(p);
        if (pos.x < 0 
            || pos.y < 0 
            || pos.x > this.frame.width() 
            || pos.y > this.frame.height()) {
            return [undefined, undefined];;
        }
        const inBound = (ele: JQuery<HTMLElement>) => {
            const offset = ele.offset();
            if (offset.left > pos.x || offset.top > pos.y) {
                return false;
            }
            if (offset.left + ele.width() > pos.x && offset.top + ele.height() > pos.y) {
                return true;
            }
            return false;
        };
        let lastRow = undefined;
        let parent: JQuery<HTMLElement> = this.frameBody;
        do {
            let found = false;
            parent.find('.visual-edit-row').each(function() {
                const $this = $(this);
                 if (inBound($this)) {
                    lastRow = $this;
                    parent = $this;
                    found = true;
                    return false;
                 }
            });
            if (!found) {
                break;
            }
        } while (true);
        if (!lastRow) {
            return [undefined, undefined];
        }
        let lastWeight = undefined;
        lastRow.find('.visual-edit-control').each(function() {
            const $this = $(this);
             if (inBound($this)) {
                lastWeight = $this;
                return false;
             }
        });
        return [lastRow, lastWeight];
    }

    public toggleShell(visible: boolean) {
        this.shellVisbile = visible;
        this.shell.toggleClass(EditorMobileStyle, visible);
    }

    /**
     * 反转browser的高和宽
    */
    public rotate() {
        this.resize(this.bound.height, this.bound.width);
    }

    /**
    * 缩放比例
    * @param val /100
    */
    public scale(val: number) {
        this.frameScale = val;
        this.renderTransform();
        this.emitRealSize();
        this.editor.emit(EditorEventPositionChange, this.bound.left, this.bound.top, this.frameScale);
    }

    private renderTransform() {
        const items = [];
        if (this.frameScale !== 100) {
            items.push('scale(' + (this.frameScale / 100) +')');
        }
        if (this.bound.left !== 0 || this.bound.top !== 0) {
            items.push(`translate(${this.bound.left}px, ${this.bound.top}px)`);
        }
        this.shell.css('transform', items.join(' '));
    }

    public resize(width: number, height: number);
    public resize(left: number, top: number, width: number, height: number);
    public resize(left: number, top: number, width: number, height: number, scale: number)
    public resize(left: number, top: number, width?: number, height?: number, scale?: number) {
        const maxWidth = this.editor.innerWidth;
        const maxHeight = this.editor.innerHeight;
        if (!width && !height) {
            width = left;
            height = top;
            left = width >= maxWidth ? 0 : (maxWidth - width) / 2;
            top = height >= maxHeight ? 0 : (maxHeight - height) / 2;
        }
        this.shell.css({
            width: width + 'px',
            height: height + 'px',
            transform: `translate(${left}px, ${top}px)`
        });
        this.bound = {
            left,
            top,
            height,
            width,
        };
        if (scale) {
            this.frameScale = scale;
            this.renderTransform();
        }
        this.emitRealSize();
        this.editor.emit(EditorEventPositionChange, left, top, this.frameScale);
    }

    private emitRealSize() {
        const maxWidth = this.editor.innerWidth;
        const maxHeight = this.editor.innerHeight;
        const scale = this.frameScale / 100;
        this.editor.emit(EditorEventBrowserResize, this.bound.left * scale, this.bound.top * scale, this.bound.width * scale, this.bound.height * scale, maxWidth, maxHeight);
    }

    /**
     * 重置滚动和缩放比例
     */
    public reset() {
        this.resize(this.bound.width, this.bound.height, 0, 0, 100);
    }

    public navigate(url: string) {
        this.frame.attr('src', url);
    }

    public reload() {
        this.frame[0].contentWindow.location.reload();
    }

    public navigateString(html: string) {
        this.frame.attr('src', 'data:text/html;charset=utf-8,' + encodeURI(html));
    }

    /**
     * 获取所有的数据
     * @returns 
     */
    public serialize() {
        if (!this.frameBody) {
            return [];
        }
        const data = [];
        this.frameBody.find<HTMLDivElement>('.visual-edit-row').each(function() {
            const row = $(this);
            const parent_id = row.attr('data-id');
            const parent_index = row.attr('data-index');
            row.children('.visual-edit-control').each(function(index) {
                const item = $(this);
                data.push({
                    id: item.attr('data-id'),
                    parent_id,
                    parent_index,
                    position: index + 1
                });
            });
        });
        return data;
    }

}

class EditorWeight {
    constructor(
        private editor: VisualEditor,
        public box: JQuery<HTMLElement>
    ) {
        
    }

    public toggle(state?: boolean) {
        this.box.toggleClass('visual-edit-mode', state);
        return this;
    }

    public toggleLoading(state?: boolean) {
        this.box.toggleClass('visual-loading', state);
        return this;
    }

    /**
     * html
     */
    public html(html?: string) {
        const newBox = $(html);
        this.box = this.box.replaceWith(newBox);
        this.box = newBox;
        return this;
    }

    /**
     * id
     */
    public id(): number {
        return EditorHelper.parseNumber(this.box.attr('data-id'));
    }

    /**
     * weightId
     */
    public weightId(): number {
        return EditorHelper.parseNumber(this.box.attr('data-weight'));
    }

    public tapRefresh() {
        this.toggleLoading(true);
        this.editor.emit(EditorEventRefreshWeight, this.id(),
        data => {
            this.toggleLoading(false);
            this.html(data.html);
        }, () => {
            this.toggleLoading(false);
        });
    }

    public tapEdit() {
        this.editor.emit(EditorEventOpenEditDialog, this);
    }

    public tapProperty() {
        this.editor.emit(EditorEventOpenProperty, this);
    }

    public tapRemove() {
        this.editor.emit(EditorEventRemoveWeight, this.id(), () => {
            this.remove();
        });
    }

    public moveTo(parent: JQuery, replace?: JQuery) {
        const pos = this.appendToPosition(parent, replace);
        this.editor.emit(EditorEventMoveWeight, {
            id: this.id(),
            parent_id: parent.attr('data-id'),
            parent_index: parent.attr('data-index'),
            position: pos
        }, () => {

        });
    }

    public appendTo(parent: JQuery, replace?: JQuery) {
        const pos = this.appendToPosition(parent, replace);
        this.box.width('auto');
        this.toggleLoading(true);
        this.editor.emit(EditorEventAddWeight, {
            weight_id: this.weightId(),
            weight_group: this.box.attr('data-group'),
            parent_id: parent.attr('data-id'),
            parent_index: parent.attr('data-index'),
            position: pos
        }, data => {
            this.toggleLoading(false);
            this.html(data.html);
        }, () => {
            this.toggleLoading(false);
        });
    }

    private appendToPosition(parent: JQuery, replace?: JQuery): number {
        if (!replace) {
            parent.append(this.box);
            return parent.children().length;
        }
        const i = parent.children().index(replace);
        if (i < 0) {
            parent.append(this.box);
            return parent.children().length;
        }
        this.box.insertBefore(replace);
        return i + 1;
    }

    private remove() {
        this.box.remove();
    }
}


class EditorScrollBar {

    private baseSize = 8;
    private element: JQuery<HTMLDivElement>;
    private innerBar: JQuery<HTMLDivElement>;

    private left = 0;
    private maxLeft = 0;
    private pxScale = 1;

    constructor(
        private editor: VisualEditor,
        private horizontal = true,
    ) {
        this.editor.on(EditorEventViewInit, () => {
            this.element = this.editor.find(this.horizontal ? '.h-scroll-bar' : '.v-scroll-bar');
            this.innerBar = this.element.find<HTMLDivElement>('.inner-bar');
            this.baseSize = this.horizontal ? this.element.height() : this.element.width();
            this.innerBar.on('mousedown', e => {
                let last: IPoint = {x: e.clientX, y: e.clientY};
                this.editor.onMouse(p => {
                    const offset = this.horizontal ? (p.x - last.x) : (p.y - last.y);
                    this.scroll(this.left + offset);
                    last = p;
                });
            });
        }).on(EditorEventBrowserResize, (left, top, width, height, maxWidth, maxHeight) => {
            if (this.horizontal) {
                this.onRender(left, maxWidth, width, this.element.width());
            } else {
                this.onRender(top, maxHeight, height, this.element.height());
            }
        });
    }

    public get barSize() {
        return this.baseSize;
    }

    /**
     * 移动多少个像素
     * @param offset 
     */
    public move(offset: number) {
        this.scroll(offset / this.pxScale);
    }

    /**
     * 滚动条滑动到多少距离, 不是实际像素
     * @param offset 
     */
    public scroll(offset: number) {
        this.left = Math.max(0, Math.min(offset, this.maxLeft));
        this.innerBar.css(this.horizontal ? 'left' : 'top', this.left + 'px');
        this.output();
    }

    /**
     * 移动百分比
     * @param percent /100 
     */
    public scrollTo(percent: number) {
        this.scroll(percent * this.maxLeft / 100);
    }

    private output() {
        this.editor.emit(EditorEventScroll, this.converter(this.left), this.horizontal);
    }

    /**
     * 滚动条滑动多少距离，转换成实际页面移动多少像素
     * @param offset 
     */
    private converter(offset: number): number {
        return offset * this.pxScale;
    }

    /**
     * 
     * @param viewSize 可见区域的
     * @param pageSize 总页面的
     * @param barSize 滚动条的长度
     */
    private onRender(offsetPx: number, viewSize: number, pageSize: number, barSize: number) {
        if (pageSize <= viewSize) {
            this.maxLeft = 0;
            this.applySize(0, barSize);
            return;
        }
        const minInnerSize = Math.min(6 * this.baseSize, this.baseSize / 2);
        const diff = pageSize - viewSize;
        const barDiff = barSize - diff;
        const innerSize = barDiff <= minInnerSize ? minInnerSize : barDiff;
        this.maxLeft = barSize - innerSize;
        this.pxScale = diff / this.maxLeft;
        this.applySize(offsetPx / this.pxScale, innerSize);
        
    }

    private applySize(offset: number, size: number) {
        this.left = Math.max(0, Math.min(offset, this.maxLeft));
        this.innerBar.css({
            [this.horizontal ? 'left' : 'top']: offset + 'px',
            [this.horizontal ? 'width' : 'height']: size + 'px',
        });
    }
}


class EditorRuler {

    private offset = 0;
    private gap = 10;
    private scale = 1;
    private foreground = '#333';
    private background = '#ccc';
    private baseWidth = 0;
    private baseX = 0;
    private baseSize = 16;

    private element: JQuery<HTMLCanvasElement>;

    constructor(
        private editor: VisualEditor,
        private horizontal = true,
    ) {
        this.editor.on(EditorEventViewInit, () => {
            this.element = this.editor.find<HTMLCanvasElement>(this.horizontal ? '.h-rule-bar' : '.v-rule-bar');
            this.baseSize = this.horizontal ? this.element.height() : this.element.width();
        }).on(EditorEventResize, (width: number, height: number) => {
            this.refresh();
            this.onAfterViewInit();
        }).on(EditorEventPositionChange, (left: number, top: number, scale: number) => {
            this.offset = this.horizontal ? left : top;
            this.scale = scale / 100;
            this.onRender(this.element[0].getContext('2d'));
        });
    }

    public get barSize() {
        return this.baseSize;
    }

    private get offsetX() {
        return this.barSize - this.offset;
    }

    /**
     * 刷新尺寸
     */
    private refresh() {
        this.baseWidth = this.horizontal ? this.editor.innerWidth : this.editor.innerHeight;
    }

    private onAfterViewInit() {
        if (this.element.length < 1) {
            return;
        }
        const canvas = this.element[0];
        if (this.horizontal) {
            canvas.width = this.baseWidth;
            canvas.height = this.barSize;
        } else {
            canvas.width = this.barSize;
            canvas.height = this.baseWidth;
        }
        this.onRender(canvas.getContext('2d'));
    }

    private createLine(event: MouseEvent): IRuleLine {
        const x = (this.horizontal ? event.clientX : event.clientY) - this.baseX;
        return {
            value: x,
            label: Math.floor((x + this.offsetX) / this.scale),
            horizontal: this.horizontal
        };
    }

    private onRender(drawingContext: CanvasRenderingContext2D) {
        drawingContext.fillStyle = this.background;
        drawingContext.lineWidth = 2;
        const h = this.barSize;
        if (this.horizontal) {
            drawingContext.fillRect(0, 0, this.baseWidth, h);
        } else {
            drawingContext.fillRect(0, 0, h, this.baseWidth);
        }
        const gap = this.gap * this.scale;
        const count = Math.ceil(this.baseWidth / gap);
        const start = Math.ceil(this.offsetX / gap);
        const fontSize = h * .3;
        for (let i = 0; i < count; i++)
        {
            const real = start + i;
            const hasLabel = real % 5 == 0;
            const x = (real * gap - this.offsetX);
            const y = h * (hasLabel ? .6 : .4);
            if (this.horizontal)
            {
                this.drawLine(drawingContext, {x, y: 0}, {x, y}, this.foreground);
                if (hasLabel)
                {
                    this.drawText(drawingContext, (real * this.gap).toString(), fontSize, this.foreground, {x, y});
                }
            } else
            {
                this.drawLine(drawingContext, {x: 0, y: x}, {x: y, y: x}, this.foreground);
                if (hasLabel)
                {
                    this.drawText(drawingContext, (real * this.gap).toString(), fontSize, this.foreground, {x: y - fontSize * 2, y: x});
                }
            }
        }
    }

    private drawText(context: CanvasRenderingContext2D, text: string, font: number, color: string, point: IPoint) {
        context.font = `normal normal bold ${font}px`;
        context.fillStyle = color;
        context.fillText(text, point.x, point.y + font);
    }

    private drawLine(
        context: CanvasRenderingContext2D,
        move: IPoint,
        line: IPoint,
        color: string
    ): void {
        context.beginPath();
        context.strokeStyle = color;
        context.moveTo(move.x, move.y);
        context.lineTo(line.x, line.y);
        context.stroke();
    }

}

class EditorTree {
    constructor(
        private box: JQuery<HTMLDivElement>,
        private itemForm: JQuery<HTMLDivElement>,
        private dataInput: JQuery<HTMLInputElement>,
        private option = {
            titleKey: 'title',
            childrenKey: 'children',
            iconKey: 'icon',
            inputPrefix: 'item_',
        }
    ) {
        const val = this.dataInput.val();
        if (val) {
            this.items = JSON.parse(val as string);
        }
        this.bindEvent();
        this.render();
    }

    private items: any[] = [];
    private editData: any;
    private parentData: any[];
    private editNode: JQuery<HTMLDivElement>;
    private parentNode: JQuery<HTMLDivElement>;

    private bindEvent() {
        const that = this;
        this.box.on('click', '.tree-add', function() {
            const parent = $(this).parent();
            that.addSource(parent);
        }).on('click', '.tree-item-line', function() {
            that.editSource($(this).closest('.tree-item'));
        }).on('click', '.tree-item-action .fa-arrow-up', function(e) {
            e.stopPropagation();
            that.move($(this).closest('.tree-item'), -1);
        }).on('click', '.tree-item-action .fa-arrow-down', function(e) {
            e.stopPropagation();
            that.move($(this).closest('.tree-item'), 1);
        }).on('click', '.tree-item-action .fa-times',  function(e) {
            e.stopPropagation();
            that.remove($(this).closest('.tree-item'));
        });
        this.itemForm.on('click', '.tree-save-btn', function() {
            that.saveSource();
        });
    }

    private getPath(item: JQuery<HTMLDivElement>): number[] {
        let parent = item.parent();
        const map = [item.index()];
        while (true) {
            if (!parent.hasClass('tree-children')) {
                break;
            }
            const ele = parent.closest('.tree-item');
            map.push(ele.index());
            parent = ele.parent();
        }
        return map.reverse();
    }

    private getSource(item: JQuery<HTMLDivElement>): [any[], number] {
        const map = this.getPath(item);
        const last = map.pop();
        let items = this.items;
        for (const i of map) {
            items = items[i][this.option.childrenKey];
        }
        return [items, last];
    }

    private remove(item: JQuery<HTMLDivElement>) {
        const [items, i] = this.getSource(item);
        items.splice(i, 1);
        if (this.editNode.is(item)) {
            this.editData = undefined;
            this.editNode = undefined;
        }
        item.remove();
        this.output();
    }

    private move(item: JQuery<HTMLDivElement>, offset: number) {
        if (offset === 0) {
            return;
        }
        const index = item.index();
        if (index == 0 && offset < 0) {
            return;
        }
        const parent = item.parent();
        const items = parent.children('.tree-item');
        if (index === items.length - 1 && offset > 0) {
            return;
        }
        if (offset < 0 && index + offset < 0) {
            offset = - index;
        } else if (offset > 0 && index + offset >= items.length) {
            offset = items.length - index - 1;
        }
        const [dataItems] = this.getSource(item);
        const target = index + offset;
        dataItems.splice(target, 0, dataItems[index]);
        dataItems.splice(index + offset < 0 ? 1 : 0, 1);
        item.insertBefore(items[target]);
        this.output();
    }

    private output() {
        this.dataInput.val(JSON.stringify(this.items));
    }

    private editSource(item: JQuery<HTMLDivElement>) {
        this.editNode = item;
        this.parentNode = item.parent();
        const [items, i] = this.getSource(item);
        this.editData = items[i];
        this.parentData = items;
        EditorHelper.eachObject(this.editData, (v, k) => {
            this.itemForm.find('*[name='+ this.option.inputPrefix +k+']').val(v).trigger('change');
        });
    }

    private addSource(parent: JQuery<HTMLDivElement>) {
        this.parentNode = parent;
        this.editData = undefined;
        this.editNode = undefined;
        this.itemForm.find('input,textarea').each(function() {
            const $this = $(this);
            const type = $this.attr('type');
            if (type === 'checkbox' || type === 'radio'
            || type === 'color' || type === 'hidden') {
                return;
            }
            $this.val('');
        });
        if (!parent.hasClass('tree-children')) {
            this.parentData = this.items;
            return;
        }
        const [items, i] = this.getSource(parent.closest('.tree-item'));
        this.parentData = items[i][this.option.childrenKey];
    }

    private saveSource() {
        if (!this.parentData) {
            this.parentData = this.items;
            this.parentNode = this.box;    
        }
        const data: any = this.editData ? this.editData : {};
        const that = this;
        this.itemForm.find('select,input,textarea').each(function() {
            const $this = $(this);
            if ($this.closest('.hidden').length > 0) {
                return;
            }
            data[$this.attr('name').substring(that.option.inputPrefix.length)] = $this.val();
        });
        if (!data[this.option.titleKey]) {
            Dialog.tip('请输入标题');
            return;
        }
        if (data.type === 'children') {
            data[this.option.childrenKey] = [];
        } else {
            delete data[this.option.childrenKey];
        }
        if (this.editNode) {
            this.editNode.replaceWith(this.renderTreeItem(data));
        } else {
            this.editData = data;
            this.parentData.push(data);
            const addNode = this.parentNode.children('.tree-add');
            addNode.before(this.renderTreeItem(data)) as any;
            this.editNode = addNode.prev() as any;
            console.log(this.editNode);
            
        }
        this.output();
    }

    private render() {
        this.box.html(this.renderTree(this.items));
    }

    private renderTree(items: any[]) {
        let html = '';
        for (const item of items) {
            html += this.renderTreeItem(item);
        }
        return html + `<div class="tree-add">
        <i class="fa fa-plus"></i>
    </div>`;
    }

    private renderTreeItem(item: any) {
        let html = '';
        if (item[this.option.childrenKey]) {
            html = '<div class="tree-children">' + this.renderTree(item[this.option.childrenKey]) + '</div>';
        }
        let icon = '';
        if (item[this.option.iconKey]) {
            icon = '<i class="fa '+ item[this.option.iconKey] +'"></i>';
        }
        const title = item[this.option.titleKey];
        return `<div class="tree-item">
        <div class="tree-item-line">
            ${icon}
            <span>${title}</span>
            <div class="tree-item-action">
                <i class="fa fa-arrow-up"></i>
                <i class="fa fa-arrow-down"></i>
                <i class="fa fa-times"></i>
            </div>
        </div>
        ${html}
    </div>`
    }
}

class EditorHelper {

    public static twoPad(i: number): string {
        return i < 10 && i >= 0 ? '0' + i : i.toString();
    }

    /**
    * 格式化数字
    * @param val 
    * @returns 
    */
    public static parseNumber(val: any): number {
        if (!val || isNaN(val)) {
            return 0;
        }
        if (typeof val === 'number') {
            return val;
        }
        if (typeof val === 'boolean') {
            return val ? 1 : 0;
        }
        if (typeof val !== 'string') {
            val = val.toString();
        }
        if (val.indexOf(',') > 0) {
            val = val.replace(/,/g, '');
        }
        if (val.indexOf('.') > 0) {
            val = parseFloat(val);
        } else {
            val = parseInt(val, 10);
        }
        return isNaN(val) ? 0 : val;
    }

    public static checkRange(val: number, min = 0, max = 100): number {
        if (val < min) {
            return min;
        }
        if (max > min && val > max) {
            return max;
        }
        return val;
    }

    /**
    * 深层次复制对象
    */
    public static cloneObject<T>(val: T): T {
        if (typeof val !== 'object') {
            return val;
        }
        if (val instanceof Array) {
            return val.map(item => {
                return EditorHelper.cloneObject(item);
            }) as any;
        }
        const res: any = {};
        for (const key in val) {
            if (Object.prototype.hasOwnProperty.call(val, key)) {
                res[key] = EditorHelper.cloneObject(val[key]);
            }
        }
        return res;
    }

    /**
    * 遍历对象属性或数组
    */
    public static eachObject(obj: any, cb: (val: any, key?: string|number) => any): any {
        if (typeof obj !== 'object') {
            return cb(obj, undefined);
        }
        if (obj instanceof Array) {
            for (let i = 0; i < obj.length; i++) {
                if (cb(obj[i], i) === false) {
                    return false;
                }
            }
            return;
        }
        for (const key in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, key)) {
                if (cb(obj[key], key) === false) {
                    return false;
                }
            }
        }
    }

    public static colorToRgba(color: string): IRgbaColor {
        const code = color.charAt(0);
        const matchFunc = (val: string): number[] => {
            return val.substring(val.indexOf('(') + 1, val.indexOf(')')).split(',').map(i => toFloat(i));
        };
        if (code === 'h') {
            const items = matchFunc(color);
            return this.hslToRgba({h: items[0], s: items[1], l: items[2], a: 1});
        } else if (code === 'r') {
            const items = matchFunc(color);
            if (items.length > 3) {
                return {r: items[0], g: items[1], b: items[2], a: items[3]};
            }
            return {r: items[0], g: items[1], b: items[2], a: 1};
        }
        return this.hexToRgba(color);
    }

    public static rgbaToHex(color: IRgbaColor): string {
        return ['#', color.r.toString(16), color.g.toString(16), color.b.toString(16), color.a >= 0 && color.a < 1 ? Math.round(color.a * 255).toString(16) : ''].join('');
    }

    public static hexToRgba(hex: string): IRgbaColor {
        if (hex.charAt(0) === '#') {
            hex = hex.substring(1);
        }
        let r: number, g: number, b: number = 0;
        let a: number = 1;
        const toHex = (val: string, isDouble = false) => {
            if (!val) {
                return 0;
            }
            const i = parseInt(val, 16);
            if (!isDouble) {
                return i;
            }
            return i * 17;
        };
        const step = hex.length <= 4 ? 1 : 2;
        r = toHex(hex.substring(0, step), step === 2);
        g = toHex(hex.substring(step, step * 2), step === 2);
        b = toHex(hex.substring(step * 2, step * 3), step === 2);
        if (hex.length > step * 3) {
            a = toHex(hex.substring(step * 3, step * 4), step === 2);
        }
        return { r, g, b, a };
    }

    public static rgbaToHsl(color: IRgbaColor): IHslColor {
        let r = color.r;
        let g = color.g;
        let b = color.b;
        r /= 255, g /= 255, b /= 255;
        let max = Math.max(r, g, b), min = Math.min(r, g, b);
        let h: number, s: number, l: number = (max + min) / 2;
        if (max == min) {
            h = s = 0; // achromatic
        } else {
            let d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }
            h /= 6;
        }
        return { h, s, l, a: color.a};
    }

    public static hslToRgba(color: IHslColor): IRgbaColor {
        let h = color.h / 360, s = color.s / 100, l = color.l / 100;
        let r: number, g: number, b: number;
        if (s === 0) {
            r = g = b = l; // achromatic
        } else {
            const hue2rgb = (p: number, q: number, t: number) => {
                if (t < 0) t += 1;
                if (t > 1) t -= 1;
                if (t < 1 / 6) return p + (q - p) * 6 * t;
                if (t < 1 / 2) return q;
                if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
                return p;
            }
            let q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            let p = 2 * l - q;
            r = hue2rgb(p, q, h + 1 / 3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1 / 3);
        }
        return { r: Math.round(r * 255), g: Math.round(g * 255), b: Math.round(b * 255), a: color.a };
    }
}

class EditorHtmlHelper {
    private static _guid = 0;
    private static _cacheData: any = {};
    public static guid(): number {
        return this._guid ++;
    }

    public static clear() {
        this._cacheData = {};
    }

    public static set(data: any): string {
        const key = 'cache'+ this.guid();
        this._cacheData[key] = data;
        return key;
    }

    public static get<T>(key: string): T {
        return this._cacheData[key];
    }

    public static value(val: any, def: any = ''): string {
        if (typeof val === 'undefined') {
            return def;
        }
        if (val === null) {
            return def;
        }
        return val;
    }

    public static join(...items: any[]): string {
        return items.map(val => this.value(val)).join('');
    }

    public static mapJoinHtml(data: object, cb: (val: any, key?: string|number) => string): string {
        let html = '';
        EditorHelper.eachObject(data, (val, key) => {
            html += cb(val, key);
        });
        return html;
    }

    public static horizontalAlign(id: string = 'horizontal-align', name: string = 'horizontal-align', value?: any, isTextAlign = false): string {
        const items = [
            {name: '<i class="fa fa-align-left" title="左对齐"></i>', value: 'left'},
            {name: '<i class="fa fa-align-center" title="水平居中"></i>', value: 'center'},
            {name: '<i class="fa fa-align-right" title="右对齐"></i>', value: 'right'},
        ];
        if (!isTextAlign) {
            items.push({name: '<i class="fa fa-align-justify" title="水平等间距"></i>', value: 'stretch'});
        }
        return this.radioInput(id, name, items, value);
    }

    public static verticalAlign(id: string = 'vertical-align', name: string = 'vertical-align', value?: any): string {
        return this.radioInput(id, name, [
            {name: '<i class="fa fa-align-left fa-vertical" title="上对齐"></i>', value: 'top'},
            {name: '<i class="fa fa-align-center fa-vertical" title="垂直居中"></i>', value: 'center'},
            {name: '<i class="fa fa-align-right fa-vertical" title="下对齐"></i>', value: 'bottom'},
            {name: '<i class="fa fa-align-justify fa-vertical" title="垂直等间距"></i>', value: 'stretch'},
        ], value);
    }


    public static positionSide(type: string, val?: any) {
        const name = `settings[style][position][value]`;
        return type !== 'static' ? this.boundInput(this.nameToId(name), name, val) : '';
    }


    /**
     * 生成原生select
     * @param id 
     * @param name 
     * @param items 
     * @param val 
     * @returns 
     */
    private static selectControl(id: string, name: string, items: IItem[], val?: any, input?: IVisualInput) {
        return `<select class="form-control" name="${name}" id="${id}"${this.renderAttribute(input)}>
        ${this.selectOption(items, val)}
        </select>`;
    }

    private static sideInput(name: string, label: string, vals: string[] = []) {
        const id = this.nameToId(name) + '_' + this.guid();
        return this.input(id, label, this.boundInput(id, name, vals), 'control-line-group');
    }

    public static switch(id: string, name: string, val: string|number|boolean, onLabel = '开启', offLabel = '关闭') {
        val = EditorHelper.parseNumber(val);
        return this.join('<div id="', id,'" class="switch-input" data-on="',onLabel,'" data-off="', offLabel ,'"><span class="switch-control"></span><span class="switch-label">', val > 0 ? onLabel : offLabel ,'</span><input type="hidden" name="', name,'" value="', val, '"/></div>');
    }

    private static radioInput(id: string, name: string, items: IItem[], selected?: string| number) {
        return this.join('<div class="control-row">', ...items.map((item, j) => {
            const index = [id, j].join('_');
            const chk = item.value == selected ? ' checked' : '';
            return `<span class="radio-control-item" data-value="${item.value}">${item.name}</span>`;
        }), `<input type="hidden" name="${name}" value="${this.value(selected)}">`, '</div>');
    }

    private static checkbox(id: string, name: string, items: IItem[], val: string[] = []) {
        return this.join('<div class="control-row">', ...items.map((item, j) => {
            const index = [id, j].join('_');
            const chk = val && val.indexOf(item.value) >= 0 ? ' checked' : '';
            return `<span class="check-label radio-control-item"><input type="checkbox" id="${index}" name="${name}" value="${item.value}"${chk}><label for="${index}">${item.name}</label></span>`;
        }), '</div>');
    }

    private static text(id: string, name: string, val: string = '', size?: number) {
        const option = size ? ` size="${size}"` : '';
        val = this.value(val);
        return `<input type="text" class="form-control" id="${id}" name="${name}" value="${val}"${option}>`;
    }

    private static input(id: string, label: string, content: string, cls: string = 'control-inline-group'): string {
        return `<div class="${cls}"><i class="control-updated-tag hidden"></i><label for="${id}">${label}</label>${content}</div>`;
    }

    public static buttonGroup(...items) {
        return this.join('<div class="btn-group control-offset">', ...items, '</div>');
    }

    public static button(text: string, cls: string) {
        return `<button type="button" class="btn ${cls}">${text}</button>`;
    }

    private static animationInput() {
        // 动效名称
        const animationLabelOptions = [
            { value: '', name: '无' },
            { value: 'bounce', name: '弹跳' },
            { value: 'fadeIn', name: '渐现' },
            { value: 'fadeOut', name: '渐出' },
            { value: 'flash', name: '闪烁' },
            { value: 'pulse', name: '跳动' },
            { value: 'rubberBand', name: '橡皮筋' },
            { value: 'shake', name: '抖动' },
            { value: 'swing', name: '摆动' },
            { value: 'tada', name: '哒嘟' },
            { value: 'wobble', name: '摇晃' },
            { value: 'jello', name: '扭曲抖动' },
            { value: 'bounceIn', name: '弹入' },
            { value: 'bounceInDown', name: '上弹入' },
            { value: 'bounceInLeft', name: '左弹入' },
            { value: 'bounceInRight', name: '右弹入' },
            { value: 'bounceInUp', name: '下弹入' },
            { value: 'flipInX', name: '水平翻转' },
            { value: 'flipInY', name: '垂直翻转' },
            { value: 'spinning', name: '旋转（顺时针）' },
            { value: 'spinning-reverse', name: '旋转（逆时针）' },
            { value: 'rotateIn', name: '旋入' },
            { value: 'rotateInDownLeft', name: '左下旋转' },
            { value: 'rotateInDownRight', name: '右下旋转' },
            { value: 'rotateInUpLeft', name: '左上旋转' },
            { value: 'rotateInUpRight', name: '右上旋转' },
            { value: 'slideInDown', name: '上滑入' },
            { value: 'slideInLeft', name: '左滑入' },
            { value: 'slideInRight', name: '右滑入' },
            { value: 'slideInUp', name: '下滑入' },
            { value: 'zoomIn', name: '逐渐放大' },
            { value: 'zoomInDown', name: '从下放大' },
            { value: 'zoomInLeft', name: '从左放大' },
            { value: 'zoomInRight', name: '从右放大' },
            { value: 'zoomInUp', name: '从上放大' },
            { value: 'rollIn', name: '滚入' },
            { value: 'lightSpeedIn', name: '闪入' },
        ];

        // 延时时间
        const animationLabelDelayOptions = [
            { value: '', name: '无' },
            { value: '0.1s', name: '100ms' },
            { value: '0.2s', name: '200ms' },
            { value: '0.3s', name: '300ms' },
            { value: '0.5s', name: '500ms' },
            { value: '1s', name: '1s' },
            { value: '2s', name: '2s' },
            { value: '3s', name: '3s' },
            { value: '4s', name: '4s' },
            { value: '5s', name: '5s' },
            { value: '6s', name: '6s' },
            { value: '7s', name: '7s' },
            { value: '8s', name: '8s' },
            { value: '9s', name: '9s' },
            { value: '10s', name: '10s' },
        ];

        // 时长
        const animationLabelDurationOptions = [
            { value: '0.25s', name: '250ms' },
            { value: '0.5s', name: '500ms' },
            { value: '0.75s', name: '750ms' },
            { value: '1s', name: '1s' },
            { value: '2s', name: '2s' },
            { value: '3s', name: '3s' },
            { value: '4s', name: '4s' },
            { value: '5s', name: '5s' },
        ];

        // 重复次数
        const animationIterationCountOptions = [
            { value: '1', name: '1' },
            { value: '2', name: '2' },
            { value: '3', name: '3' },
            { value: '4', name: '4' },
            { value: '5', name: '5' },
            { value: '6', name: '6' },
            { value: '7', name: '7' },
            { value: '8', name: '8' },
            { value: '9', name: '9' },
            { value: '10', name: '10' },
            { value: 'infinite', name: '无限循环' },
        ];

        const animationFuncOptions = [
            { value: 'linear', name: '线性' },
            { value: 'ease', name: 'ease' },
            { value: 'ease-in', name: 'ease-in' },
            { value: 'ease-out', name: 'ease-out' },
            { value: 'ease-in-out', name: 'ease-in-out' },
        ];
        return `<div class="animation-container">
        <div class="effect-show">效果展示</div>
        <div class="control-inline-group">
            <label for="animation-type">动效</label>
            <select class="form-control" id="animation-type" name="animation[type]">
            ${this.selectOption(animationLabelOptions)}
            </select>
        </div>
        <div class="control-inline-group">
            <label for="animation-delay">延时</label>
            <select class="form-control" id="animation-delay" name="animation[delay]">
            ${this.selectOption(animationLabelDelayOptions)}
            </select>
        </div>
        <div class="control-inline-group">
            <label for="animation-duratio">时长</label>
            <select class="form-control" id="animation-duration" name="animation[duration]">
            ${this.selectOption( animationLabelDurationOptions)}
            </select>
        </div>
        <div class="control-inline-group">
            <label for="animation-count">重复</label>
            <select class="form-control" id="animation-count" name="animation[count]">
            ${this.selectOption(animationIterationCountOptions)}
            </select>
        </div>
    </div>`;
    }

    private static backgroundPopup(id: string, name: string, val?: any) {
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
        <input type="hidden" name="${name}" value="${this.value(val)}">
    </div>
    <div class="control-popup" data-popup="background">
        <div class="control-inline-group">
            <label for="">背景色</label>
            ${this.colorPopup(id, name + '[color]')}
        </div>
        <div class="control-line-group">
            <label for="">背景图片</label>
            ${this.imageInput(id, name + '[image]')}
        </div>
    </div>
    `;
    }

    private static imageInput(id: string, name: string, value?: any) {
        if (!value) {
            return `<label class="drag-control-container" for="${id}">
            拖放文件
            <p>(或点击)</p>
            <input type="file" id="${id}" name="${name}" accept="image/*">
            <div class="loading">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </label>`;
        }
        return `<div class="image-control-item">
            <div class="control-body">
                <img src="${value}">
            </div>
            <div class="control-action"></div>
                <i class="fa fa-edit"></i>
                <i class="fa fa-trash"></i>
            </div>
        </div>`;
    }

    private static shadowPopup(id: string, name: string, val?: any) {
        const html = ['X', 'Y', 'BLUR', 'SPREAD'].map((label, i) => {
            return this.sizeInput(label, id + '-' + i, `${name}[${i}]`)
        });
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
        <input type="hidden" name="${name}" value="${val}">
    </div>
    <div class="control-popup" data-popup="shadow">
        <div class="control-row">
            ${html.join('')}
        </div>
       
        <div class="control-inline-group">
            <label for="">Color</label>
            ${this.colorPopup(id, name + '[color]')}
        </div>
        <div class="control-inline-group">
            <label for="">Inset</label>
            ${this.switch(id,name + '[inset]', '', '内', '外')}
        </div>
    </div>`;
    }

    private static selectOption(items: IItem[], selected?: any) {
        return items.map(item => {
            const sel = selected === item.value ? ' selected' : '';
            return `<option value="${item.value}"${sel}>${item.name}</option>`;
        }).join('');
    }

    private static borderPopup(id: string, name: string, val?: any) {
        const styleItems = [
            {name: '无', value: ''},
            {name: '横线', value: 'solid'},
            {name: '点线', value: 'dotted'},
            {name: '虚线', value: 'double'},
        ];
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
        <input type="hidden" name="${name}" value="${val}">
    </div>
    <div class="control-popup" data-popup="border">
        <div class="control-line-group">
            <label for="">Width</label>
            ${this.boundInput(id,name+'[width]')}
        </div>
        <div class="control-inline-group">
            <label for="">Style</label>
            <select name="${name+'[style]'}">
                ${this.selectOption(styleItems)}
            </select>
        </div>
        <div class="control-inline-group">
            <label for="">Color</label>
            ${this.colorPopup(id,name+'[color]')}
        </div>
        <div class="control-line-group">
            <label for="">Radius</label>
            ${this.radiusInput(id,name+'[radius]')}
        </div>
        <div class="control-inline-group">
            <label for="">Shadow</label>
            ${this.shadowPopup(id,name+'[shadow]')}
        </div>
    </div>`;
    }

    private static iconPopup(id: string, name: string, val?: any) {
        const iconItems = [
            {name: 'Home', value: 'fa-home'},
            {name: 'Edit', value: 'fa-edit'},
            {name: 'close', value: 'fa-times'},
            {name: 'trash', value: 'fa-trash'},
        ];
        const html = iconItems.map(item => `<div class="icon-option-item" data-value="${item.value}">
        <i class="fa ${item.value}"></i>
        <span>${item.name}</span>
        </div>`).join('')
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
        <input type="hidden" name="${name}" value="${val}">
    </div>
    <div class="control-popup" data-popup="icon">
        <div class="search-header-bar">
            <input>
            <i class="fa fa-search"></i>
        </div>
        <div class="search-body">
            ${html}
        </div>
    </div>`;
    }

    private static colorPopup(id: string, name: string, val?: any) {
        // return `<input type="color" class="form-control-color" id="${id}" name="${name}">`;
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
        <input type="hidden" name="${name}" value="${val}">
    </div>
    <div class="control-popup" data-popup="color">
        <div class="popup-action">
            <div class="btn btn-danger">清除</div>
        </div>
        <div class="control-line-group mt-0">
            <label for="">Hue</label>
            <input type="range" class="hue-bar" min="0" max="360" step="1" value="0">
        </div>
        <div class="control-line-group mt-0">
            <label for="">Saturation</label>
            <input type="range" class="saturation-bar" min="0" max="100" step="1" value="0">
        </div>
        <div class="control-line-group mt-0">
            <label for="">Lightness</label>
            <input type="range" class="lightness-bar" min="0" max="100" step="1" value="0">
        </div>
        <div class="control-line-group alpha-ouline-bar mt-0">
            <label for="">Transparency</label>
            <input type="range" class="alpha-bar" min="0" max="100" step="1" value="1">
        </div>
    
        <div class="control-row tab-bar-target active">
            <div class="control-half-group">
                <input type="text">
                <label for="">HEX</label>
            </div>
        </div>
        <div class="control-row tab-bar-target">
            <div class="control-half-group">
                <input type="number">
                <label for="">R</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">G</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">B</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">A</label>
            </div>
        </div>
        <div class="control-row tab-bar-target">
            <div class="control-half-group">
                <input type="number">
                <label for="">H</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">S</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">L</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">A</label>
            </div>
        </div>
        <div class="tab-bar">
            <a class="item active">HEX</a>
            <a class="item">RGB</a>
            <a class="item">HSL</a>
        </div>
    </div>`;
    }
    public static colorPopupColor(popup: JQuery<HTMLDivElement>): IHslColor;
    public static colorPopupColor(popup: JQuery<HTMLDivElement>, color: string): void;
    public static colorPopupColor(popup: JQuery<HTMLDivElement>, color?: string): void|IHslColor {
        const hCtl = popup.find('.hue-bar');
        const sCtl = popup.find('.saturation-bar');
        const lCtl = popup.find('.lightness-bar');
        const tCtl = popup.find('.alpha-bar');
        if (typeof color === 'undefined') {
            return {h: toInt(hCtl.val()), s: toInt(sCtl.val()), l: toInt(lCtl.val()), a: toInt(tCtl.val()) / 100};
        }
        const format = EditorHelper.colorToRgba(color);
        const hsl = EditorHelper.rgbaToHsl(format);
        hCtl.val(hsl.h);
        sCtl.val(hsl.s);
        lCtl.val(hsl.l);
        tCtl.val(hsl.a * 100);
    }

    private static boundInput(id: string, name: string, items?: any[]) {
        const html = ['上', '右', '下', '左'].map((label, i) => {
            return this.sizeInput(label, `${id}_${i}`, `${name}[${i}]`, items && items.length > i ? items[i] : '');
        }).join('');
        return `<div class="control-row">${html}</div>`
    }


    private static maskInput() {
        return `<div class="mark-container">
        <div class="clip-path-box">
            <div class="shape" style="clip-path: inset(10%);"></div>
            <div class="shape" style="clip-path: circle(45% at 50% 50%);"></div>
            <div class="shape" style="clip-path: ellipse(30% 45% at 50% 50%);"></div>
            <div class="shape"
                style="clip-path: polygon(50% 0%, 0% 100%, 100% 100%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);"
            ></div>
        </div>
        <button class="btn btn-primary">一键取消</button>
    </div>
    `;
    }


    private static radiusInput(id: string, name: string, items?: any[]) {
        const html = ['左上', '右上', '右下', '左下'].map((label, i) => {
            return this.sizeInput(label, `${id}_${i}`, `${name}[]`, items && items.length > i ? items[i] : '');
        }).join('');
        return `<div class="control-row">${html}</div>`
    }

    private static selectInput(id: string, name: string, val: any, items: string[]|IItem[], editable = false, searchable = false, arrow = 'fa-angle-down') {
        const body = editable ? `<input type="text" class="input-body" id="${id}" name="${name}" value="${val}">` : `<span class="input-body">${val}</span>`;

        return this.join(`<div class="select-control-container">`, `<div class="select-input-container">
        ${body}
        <div class="input-clear">
            <i class="fa fa-close"></i>
        </div>
        <div class="input-arrow">
            <i class="fa ${arrow}"></i>
        </div>
    </div>`, this.selectOptionBar(items, val, searchable), `</div>`);

    }

    private static sizeInput(label: string|undefined, id?: string, name?: string, val?: any, unit: string = 'px') {
        val = toFloat(val);
        const input = `<input type="number" id="${id}" name="${name}[value]" value="${val}">`;
        const core = label ? `<div class="control-body">
        <label for="${id}">${label}</label>
        ${input}
        <input class="extra-control" name="${name}[unit]" value="${unit}" spellcheck="false">
    </div>` : `<div class="control-body">
    <input class="extra-control" name="${name}[unit]" value="${unit}" spellcheck="false">
    ${input}
</div>`;
        return `<div class="select-with-control">` + core + this.selectOptionBar(['px', 'em', 'rem', 'vh', 'vw', '%', 'auto', 'none']) + '</div>';
    }

    private static selectOptionBar(items: string[]|IItem[], selected?: any, searchable = false) {
        let option = searchable ? `<div class="search-option-item">
        <input type="text">
        <i class="fa fa-search"></i>
    </div>` : '';
        option += items.map(item => {
            const name = typeof item === 'object' ? item.name : item;
            const value = typeof item === 'object' ? item.value : item;
            const sel = value === selected ? ' selected' : '';
            return `<div class="option-item${sel}" data-value="${value}">${name}</div>`;
        }).join('');
        return `<div class="select-option-bar">${option}</div>`
    }

    private static treeInput(input: IVisualInput[], items?: any[]) {
        let html = '';
        if (items) {
            for (const item of items) {
                html += this.renderTreeItem(input, item);
            }
        }
        return `${html}<div class="tree-add-btn">
        <i class="fa fa-plus"></i>
    </div>`;
    }

    private static renderMultipleItem(input: IVisualInput[], value?: any) {
        let icon = '';
        if (value && value.icon) {
            icon = '<i class="item-icon fa '+ value.icon +'"></i>';
        }
        const title = value ? value.title : '';
        return `<div class="list-item">
        ${icon}
            <span class="item-title">${title}</span>
            <div class="item-action-icon">
                <i class="fa fa-ellipsis-h"></i>
                <div class="item-action-bar">
                    <i class="fa fa-trash"></i>
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-arrow-up"></i>
                    <i class="fa fa-arrow-down"></i>
                </div>
            </div>
        </div>`
        // return `<div class="multiple-control-item">
        //         <div class="multiple-control-header">
        //             <span>${value?.title}</span>
        //             <div class="control-action">
        //                 <i class="fa fa-edit"></i>
        //                 <i class="fa fa-trash"></i>
        //             </div>
        //         </div>
        //         <div class="multiple-control-body">
        //         ${this.renderForm(input, value)}
        //         </div>
        //     </div>`;
         
    }
    
    private static renderTreeItem(input: IVisualInput[], item?: any) {
        let html = '';
        let icon = '';
        if (item && item.children) {
            html = '<div class="item-children">' + this.treeInput(input, item.children) + '</div>';
            icon = '<div class="item-open-icon"><i class="fa fa-chevron-right"></i></div>';
        }
        if (item && item.icon) {
            icon = '<i class="item-icon fa '+ item.icon +'"></i>';
        }
        const title = item ? item.title : '';
        return `<div class="tree-item">
        <div class="item-body">
            ${icon}
            <span class="item-title">${title}</span>
            <div class="item-action-icon">
                <i class="fa fa-ellipsis-h"></i>
                <div class="item-action-bar">
                    <i class="fa fa-trash"></i>
                    <i class="fa fa-plus"></i>
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-arrow-up"></i>
                    <i class="fa fa-arrow-down"></i>
                </div>
            </div>
        </div>
        ${html}
    </div>`
    }
    private static nameToId(name: string) {
        return name.replace(/\[/g, '_').replace(/\]/g, '');
    }

    private static renderAttribute(item: IVisualInput): string {
        if (!item || !item.option) {
            return;
        }
        const items = [];
        EditorHelper.eachObject(item.option, (val, key) => {
            if (typeof key === 'string' && key.indexOf('data-') === 0) {
                items.push(`${key}="${val}"`);
            }
        });
        return items.join(' ');
    }

    private static renderClass(item: IVisualInput, hasKey = true): string {
        if (!item) {
            return;
        }
        const items = [];
        if (item.class) {
            items.push(item.class);
        }
        if (item.option?.tab) {
            items.push(item.option.tab);
        }
        if (item.hidden) {
            items.push('hidden');
        }
        if (items.length === 0) {
            return '';
        }
        const cls = items.join(' ');
        return hasKey ? ` class="${cls}"` : cls;
    }

    private static isHiddenTab(tab: string, items: any[]): boolean {
        for (const item of items) {
            if (tab.indexOf(item.tab) === 0) {
                return tab !== item.match;
            }
        }
        return false;
    }

    public static render(form: IVisualInput[]): string {
        const tabItems = [];
        for (const item of form) {
            if (!item.option) {
                continue;
            }
            if (item.option['data-tab']) {
                tabItems.push({tab: item.option['data-tab'], match: item.option['data-tab'] + '-' + (item.value ?? item.items[0].value)});
                continue;
            }
            if (!item.option.tab) {
                continue;
            }
            item.hidden = this.isHiddenTab(item.option.tab, tabItems);
        }
        return form.map(this.renderInput.bind(this)).join('');
    }

    public static formData(target: JQuery<HTMLElement>): Object;
    public static formData(target: JQuery<HTMLElement>, isObject: false): string;
    public static formData(target: JQuery<HTMLElement>, isObject: true): Object;
    public static formData(target: JQuery<HTMLElement>, prefix: string): Object;
    public static formData(target: JQuery<HTMLElement>, isObject: string|boolean = true): any {
        const that = this;
        const prefix = typeof isObject === 'string' ? isObject : '';
        isObject = typeof isObject === 'string' || isObject;
        const data: any = isObject ? {} : [];
        target.find('input,textarea,select,.multiple-container,.tree-container').each(function(this: HTMLInputElement) {
            const $this = $(this);
            if ($this.hasClass('multiple-container') || $this.hasClass('.tree-container')) {
                const input = that.get<IVisualInput>($this.data('cache'));
                if (isObject) {
                    data[input.name] = input.items;
                    return;
                }
                data.push(encodeURIComponent(input.name) + '=' +
                    encodeURIComponent( JSON.stringify(input.items) ));
                return;
            }
            if (this.type && ['radio', 'checkbox'].indexOf(this.type) >= 0 && !this.checked) {
                return;
            }
            if (!this.name) {
                return;
            }
            const name = prefix && this.name.indexOf(prefix) === 0 ? this.name.substring(prefix.length) : this.name;
            if (isObject) {
                data[name] = $(this).val();
                return;
            }
            data.push(encodeURIComponent(name) + '=' +
                    encodeURIComponent( $(this).val().toString() ));
        });
        return isObject ? data : data.join('&');
    }

    private static renderForm(form: IVisualInput[], data?: any): string {
        return this.render(form.map(item => {
            return {...item, value: this.inputValue(data, item.name)};
        }));
    }

    private static inputValue(data: any, key: string): any {
        if (typeof data !== 'object' || !key) {
            return undefined;
        }
        if (Object.prototype.hasOwnProperty.call(data, key)) {
            return data[key];
        }
        if (key.indexOf('item_') !== 0) {
            return undefined;
        }
        key = key.substring(5);
        return Object.prototype.hasOwnProperty.call(data, key) ? data[key] : undefined;
    }

    private static renderInput(input: IVisualInput): string {
        const id = input.name + '_' + this.guid();
        const func = this[input.type + 'Execute'];
        if (typeof func === 'function') {
            return func.call(this, input, id);
        }
        return this.textExecute(input, id);
        // throw new Error(`input type error:[${input.type}]`);
    }

    private static groupExecute(input: IVisualInput) {
        if (!input.label) {
            return `<div${this.renderClass(input)}>${this.render(input.items)}</div>`
        }
        return `<div class="expand-box">
        <div class="expand-header">${input.label}<span class="fa fa-chevron-right"></span></div>
        <div class="expand-body">
        ${this.render(input.items)}
        </div>
    </div>`
    }

    private static alignExecute(input: IVisualInput, id: string) {
        return this.horizontalAlign() + this.verticalAlign();
    }

    private static borderExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.borderPopup(id, input.name, input.value));
    }


    private static textAlignExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.horizontalAlign(id, input.name, input.value, true), 'control-line-group');
    }
    
    private static colorExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.colorPopup(id, input.name, input.value));
    }

    private static backgroundExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.backgroundPopup(id, input.name, input.value));
    }

    private static iconExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.iconPopup(id, input.name, input.value));
    }

    private static numberExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, `<input type="number" id="${id}" name="${input.name}" value="${input.value}">`);
    }

    private static textareaExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, `<textarea class="form-control" id="${id}" name="${input.name}">${input.value}</textarea>`, 'control-line-group');
    }

    private static sizeExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.sizeInput(undefined, id, input.name, input.value));
    }

    private static rangeExecute(input: IVisualInput, id: string) {
        const items = [];
        for (let i = input.min; i <= input.max; i+= input.step) {
            items.push({name: i, value: i});
        }
        return this.input(id, input.label, this.selectControl(id, input.name, items, input.value, input));
    }

    private static selectExecute(input: IVisualInput, id: string) {
        if (!input.search && input.items) {
            return this.input(id, input.label, this.selectControl(id, input.name, input.items, input.value, input));
        }
        return this.input(id, input.label, this.selectInput(id, input.name, input.items, input.value));
    }

    private static positionExecute(input: IVisualInput, id: string) {
        const data = input.value; 
        const typeItems = [
            {name: '无', value: 'static'},
            {name: '相对定位', value: 'relative'},
            {name: '绝对定位', value: 'absolute'},
            {name: '固定定位', value: 'fixed'},
        ];
        const type = !data.type ? typeItems[0].value : data.type;
        const name = `${input.name}[value]`;
        const bound = type !== typeItems[0].value ? this.boundInput(this.nameToId(name), name, data.value) : '';
        const select = this.input(id, input.label, this.selectControl(id, input.name + '[type]', typeItems, type));
        return  `<div class="position-container">
        ${select}
        <div>${bound}</div>
    </div>`
    }

    private static boundExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.boundInput(id, input.name, input.value)
        , 'control-line-group');
    }

    private static switchExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.switch(id, input.name, input.value));
    }

    private static textExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, input.items && input.items.length > 0 ?  this.selectInput(id, input.name, input.value, input.items, true, false, 'fa-bolt') : this.text(id, input.name, input.value)
        , 'control-line-group');
    }

    private static imageExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.imageInput(id, input.name, input.value)
        , 'control-line-group');
    }

    private static imagesExecute(input: IVisualInput, id: string) {
        const items = [];
        const name = input.name + '[]';
        if (input.items) {
            for (const item of input.items) {
                items.push(this.imageInput(id, name, item));
            }
        }
        items.push(this.imageInput(id, name));
        return this.input(id, input.label, 
            items.join('')
        , 'control-line-group');
    }

    private static multipleExecute(input: IVisualInput, id: string) {
        const items = [];
        const name = input.name + '[]';
        if (input.items) {
            for (const item of input.items) {
                items.push(this.renderMultipleItem(input.input, item));
            }
        }
        items.push(`<div class="multiple-add-btn">
            <i class="fa fa-plus"></i>
        </div>`);
        return this.input(id, input.label, 
            this.flipPanel(`<div class="multiple-container" data-cache="${this.set(input)}">${items.join('')}</div>`)
        , 'control-line-group');
    }

    private static treeExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, 
            this.flipPanel(`<div class="tree-container" data-cache="${this.set(input)}">${this.treeInput(input.input, input.items)}</div>`)
        , 'control-line-group');
    }

    private static flipPanel(body: string) {
        return `<div class="flip-container"><div class="flip-front-body">${body}</div><div class="flip-back-body">
            <div class="flip-action-bar">
                <i class="fa fa-arrow-left flip-back-btn"></i>
                <i class="fa fa-save flip-save-btn"></i>
            </div>
            <div class="flip-body"></div>
        </div></div>`;
    }

    private static htmlExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, `<textarea class="form-control" id="${id}" name="${input.name}">${input.value}</textarea>`, 'control-line-group');
    }

    private static getData(data: any[], index: number[]): any {
        let i = index.length - 1;
        while (i >= 0) {
            const j = index[i];
            if (j >= data.length) {
                return undefined;
            }
            const res = data[j];
            if (i === 0) {
                return res;
            }
            data = res.children;
            i --;
        }
        return undefined;
    }

    private static setData(data: any[], index: number[], val: any) {
        let i = index.length - 1;
        while (i >= 0) {
            const j = index[i];
            if (i === 0) {
                if (j >= data.length) {
                    data.push({...val});
                } else {
                    data[j] = {...val};
                }
            }
            if (!data[j].children) {
                data[j].children = [];
            }
            data = data[j].children;
            i --;
        }
    }

    private static moveItem(data: any[], source: number[], dist: number[]) {
        let item: any;
        let temp = data;
        let i = source.length - 1;
        while (i >= 0) {
            const j = source[i];
            if (i === 0) {
                if (j < temp.length) {
                    item = temp[j];
                    temp.splice(j, 1);
                }
                break;
            }
            if (!temp[j].children) {
                temp[j].children = [];
            }
            temp = temp[j].children;
            i --;
        }
        if (!item) {
            return;
        }
        temp = data;
        i = dist.length - 1;
        while (i >= 0) {
            const j = dist[i];
            if (i === 0) {
                if (j < temp.length) {
                    temp.splice(j, 0, item);
                } else {
                    temp.push(item);
                }
                break;
            }
            if (!temp[j].children) {
                temp[j].children = [];
            }
            temp = temp[j].children;
            i --;
        }
    }

    private static removeItem(data: any[], index: number[]) {
        let i = index.length - 1;
        while (i >= 0) {
            const j = index[i];
            if (i === 0) {
                if (j < data.length) {
                    data.splice(j, 1);
                }
                return;
            }
            if (!data[j].children) {
                data[j].children = [];
            }
            data = data[j].children;
            i --;
        }
    }

    public static bindInputEvent(box: JQuery<HTMLDivElement>) {
        const that = this;
        box.on('change', '.position-container select', function() {
            const $this = $(this);
            $this.next().html(EditorHtmlHelper.positionSide($this.val() as string));
        })
        // switch
        .on('click', '.switch-input', function() {
            const $this = $(this);
            const checked = !$this.hasClass('checked');
            $this.toggleClass('checked', checked);
            $this.find('.switch-label').text(checked ? $this.data('on') : $this.data('off'));
            const val = checked ? 1 : 0;
            $this.find('input').val(val).trigger('change');
            const box = $this.closest('.control-inline-group');
            box.trigger(EditorEventInputChange, val)
            if ($this.data('tab')) {
                box.trigger(EditorEventTabToggle, [$this.data('tab'), $this.val()]);
            }
        })
        .on(EditorEventInputReset, '.switch-input', function() {
            $(this).trigger('click');
        })
        // popup
        .on('click', '.control-popup-target', function() {
            const $this = $(this);
            const popup = $this.next('.control-popup');
            if ($this.closest('.dialog-box').length > 0) {
                popup.toggleClass('popup-top', $this.offset().top + $this.height() > window.innerHeight - popup.height())
            }
            popup.toggleClass('open');
        })
        .on(EditorEventInputChange, '.control-popup-target', function(e, val) {
            e.stopPropagation();
            $(this).closest('.control-inline-group').trigger(EditorEventInputChange, val);
            $(this).find('input').val(val);
        })
        .on(EditorEventInputChange, '.control-popup', function(e, val) {
            e.stopPropagation();
            $(this).prev('.control-popup-target').trigger(EditorEventInputChange, val);
        })
        .on(EditorEventInputReset, '.control-popup-target', function(e) {
            e.stopPropagation();
            const $this = $(this);
            $this.find('input').val('');
        })
        .on(EditorEventInputReset, '.control-popup', function(e) {
            e.stopPropagation();
            if (e.target !== this) {
                return;
            }
            const $this = $(this);
            const popupType = $this.data('popup');
            if (popupType === 'color') {
                EditorHtmlHelper.colorPopupColor($this, '#0000');
                $this.find('.tab-bar-target input').val('');
                return;
            }
            $this.children('.control-inline-group,.control-line-group').children('label').next().trigger(EditorEventInputReset);
        })
        .on('click', '.control-popup .popup-action .btn', function() {
            const $this = $(this);
            if ($this.hasClass('btn-danger')) {
                $this.closest('.control-popup').trigger(EditorEventInputReset).prev('.control-popup-target').trigger(EditorEventInputReset).
                prev().prev('.control-updated-tag').addClass('hidden');
                return;
            }
        })
        // icon
        .on('click', '.icon-option-item', function() {
            const $this = $(this);
            $this.addClass('selected').siblings().removeClass('selected');
            $this.closest('.control-popup').trigger(EditorEventInputChange, $this.data('value'));
        })
        .on('change', '.control-popup .search-header-bar input', function() {
            const $this = $(this);
            const val = $this.val();
            $this.closest('.search-header-bar').next('.search-body').find('.icon-option-item').each(function() {
                const item = $(this);
                const text = item.find('span').text();
                item.toggleClass('hidden', val && text.indexOf(val) < 0);
            });
        })
        // select
        .on('change', '.select-with-control .control-body input', function() {
            const $this = $(this);
            let target = $this.parent();
            if (!target.hasClass('.control-inline-group')) {
                target = target.closest('.control-line-group');
            }
            target.trigger(EditorEventInputChange, $this.val());
        })
        .on(EditorEventInputReset, '.select-with-control', function() {
            $(this).find('.control-body input').val('');
        })
        .on('click', '.select-with-control .control-body .extra-control', function() {
            $(this).closest('.select-with-control').toggleClass('select-focus');
        }).on('select:change', '.select-with-control .select-option-bar', function(_, ele: HTMLDivElement) {
            const $this = $(ele);
            $this.closest('.select-with-control').removeClass('select-focus').find('.control-body .extra-control').val($this.text());
        }).on('click', '.select-option-bar .option-item', function() {
            const $this = $(this);
            $this.addClass('selected').siblings().removeClass('selected');
            $this.closest('.select-option-bar').trigger('select:change', this);
        })
        // color
        .on('change', '.hue-bar,.saturation-bar,.lightness-bar,.alpha-bar', function() {
            const colorPopup = $(this).closest('.control-popup');
            const hsl = EditorHtmlHelper.colorPopupColor(colorPopup);
            const rgba = EditorHelper.hslToRgba(hsl);
            const format = `rgba(${rgba.r}, ${rgba.g}, ${rgba.b}, ${rgba.a})`;
            colorPopup.find('.saturation-bar').css('background-image', `linear-gradient(to right, gray, ${format})`);
            colorPopup.find('.lightness-bar').css('background-image', `linear-gradient(to right, black, ${format})`);
            colorPopup.find('.alpha-bar').css('background-image', `linear-gradient(to right, transparent, ${format})`);
            colorPopup.find('.tab-bar-target').each(function(i) {
                const that = $(this);
                if (i === 0) {
                    that.find('input').val(EditorHelper.rgbaToHex(rgba));
                } else {
                    const items = i === 1 ? [rgba.r, rgba.g, rgba.b, rgba.a] : [hsl.h, hsl.s, hsl.l, hsl.a];
                    that.find('input').each(function(this: HTMLInputElement, j) {
                        this.value = items[j].toString();
                    });
                }
            });
            colorPopup.trigger(EditorEventInputChange, EditorHelper.rgbaToHex(rgba))
        })
        // select
        .on('change', '.select-control-container .input-body', function() {
            const $this = $(this);
            const val = this instanceof HTMLInputElement ? this.value : $this.text();
            $this.next('.input-clear').toggle(!!val);
            $this.closest('.select-control-container').find('.select-option-bar .option-item').each(function() {
                const that = $(this);
                that.toggleClass('selected', val === that.data('value'));
            });
            $this.closest('.control-line-group').trigger(EditorEventInputChange, val);
        }).on('click', '.select-control-container .input-clear', function() {
            const target = $(this).prev('.input-body');
            if (target[0] instanceof HTMLInputElement) {
                target.val('');
            } else {
                target.text('');
            }
            target.trigger('change');
        }).on('click', '.select-control-container .input-arrow', function() {
            $(this).closest('.select-control-container').toggleClass('select-focus');
        }).on('select:change', '.select-control-container .select-option-bar', function(_, ele: HTMLDivElement) {
            const $this = $(ele);
            const target = $this.closest('.select-control-container').removeClass('select-focus').find('.input-body');
            if (target[0] instanceof HTMLInputElement) {
                target.val($this.data('value'));
            } else {
                target.text($this.data('value'));
            }
            target.trigger('change');
        })
        .on(EditorEventInputReset, '.select-control-container', function() {
            $(this).find('.input-clear').trigger('click');
        })
        // tabbar 
        .on('click', '.tab-bar .item', function() {
            const $this = $(this);
            $this.addClass('active').siblings().removeClass('active');
            $this.closest('.tab-bar').siblings('.tab-bar-target').removeClass('active').eq($this.index()).addClass('active');
        })
        // 图片上传
        .on('change', '.drag-control-container input[type=file]', function(this: HTMLInputElement) {
            const target = $(this).closest('.drag-control-container');
            target.addClass('is-uploading');
            
        })
        // radio
        .on(EditorEventInputReset, '.control-row', function(e) {
            if (!$(e.target).hasClass('control-row')) {
                return;
            }
            $(this).children().trigger(EditorEventInputReset);
        })
        .on('click', '.radio-control-item', function() {
            const $this = $(this);
            const isChecked = !$this.hasClass('checked');
            $this.toggleClass('checked', isChecked);
            if (!$this.hasClass('check-label') && isChecked) {
                $this.siblings().removeClass('checked');
            }
            const val = isChecked ? $this.data('value') : undefined;
            const target = $this.closest('.control-row');
            target.find('input').val(val);
            const group = target.closest('.control-line-group');
            group.trigger(EditorEventInputChange, val);
            if (target.data('tab')) {
                group.trigger(EditorEventTabToggle, [target.data('tab'), val]);
            }
        })
        .on(EditorEventInputReset, '.radio-control-item', function() {
            const $this = $(this);
            if ($this.hasClass('checked')) {
                $this.trigger('click');
            }
        })
        .on('change', 'select', function() {
            const $this = $(this);
            const val = $this.val();
            const group = $this.closest('.control-inline-group');
            group.trigger(EditorEventInputChange, val);
            if ($this.data('tab')) {
                group.trigger(EditorEventTabToggle, [$this.data('tab'), val]);
            }
        }).on(EditorEventInputReset, 'select', function(e) {
            e.stopPropagation();
            $(this).val('');
        }).on(EditorEventTabToggle, '.control-inline-group,.control-line-group', function(_, tab: string, val: any) {
            const match = `${tab}-${val}`;
            $(this).siblings().each(function(this: HTMLDivElement) {
                for (let i = 0; i < this.classList.length; i++) {
                    const item = this.classList[i];
                    if (item.indexOf(tab) === 0) {
                        $(this).toggleClass('hidden', item !== match);
                        break;
                    }
                }
            });
        }).on(EditorEventInputChange, '.control-inline-group,.control-line-group', function(e, val: any) {
            e.stopPropagation();
            const $this = $(this);
            const popup = $this.closest('.control-popup');
            if (popup.length > 0) {
                popup.trigger(EditorEventInputChange, val);
                return;
            }
            $this.find('.control-updated-tag:first').toggleClass('hidden', !val);
        })
        .on('click', '.control-updated-tag', function() {
            const $this = $(this);
            $this.next().next().trigger(EditorEventInputReset).next('.control-popup').trigger(EditorEventInputReset);
            $this.addClass('hidden');
        })
        // 多项添加
        .on('click', '.multiple-container .multiple-control-header', function() {
            $(this).closest('.multiple-control-item').toggleClass('open').siblings().removeClass('open');
        })
        // flip 
        .on('click', '.flip-container .flip-back-btn', function() {
            $(this).closest('.flip-container').trigger(EditorEventFlipFinish, false);
        })
        .on('click', '.flip-container .flip-save-btn', function() {
            $(this).closest('.flip-container').trigger(EditorEventFlipFinish, true);
        })
        .on(EditorEventFlipToggle, '.flip-container', function(_, data: {body: string, callback: (data: any) => void}) {
            const $this = $(this);
            $this.addClass('flip-toggle');
            $this.find('.flip-body').html(data.body);
            $this.one(EditorEventFlipFinish, (_, res: boolean) => {
                $this.removeClass('flip-toggle');
                if (res) {
                    data.callback(that.formData($this.find('.flip-body'), 'item_'));
                }
            });
        })
        .on(EditorEventListEdit, '.tree-container,.multiple-container', function(_, target: HTMLElement|JQuery<HTMLElement>) {
            if (target instanceof HTMLElement) {
                target = $(target);
            }
            const isTree = target.hasClass('.tree-item');
            const map = isTree ? [target.index(), ...$(this).parents('.tree-item').map((_, j) => $(j).index()).toArray()] : [target.index()];
            const formData = that.get<IVisualInput>($(this).data('cache'));
            target.closest('.flip-container').trigger(EditorEventFlipToggle, {
                body: that.renderForm(formData.input, that.getData(formData.items, map)),
                callback: data => {
                    target.replaceWith(isTree ? that.renderTreeItem(formData.input, data) : that.renderMultipleItem(formData.input, data));
                    that.setData(formData.items, map, data);
                    target.closest('.control-line-group').trigger(EditorEventInputChange, formData.items);
                }
            });
        })
        // tree 
        .on('click', '.tree-container .tree-add-btn,.multiple-container .multiple-add-btn', function() {
            const $this = $(this);
            const box = $this.closest('.tree-container,.multiple-container');
            const formData = that.get<IVisualInput>(box.data('cache')).input;
            const target = $(box.hasClass('tree-container') ? that.renderTreeItem(formData) : that.renderMultipleItem(formData));
            $this.before(target);
            box.trigger(EditorEventListEdit, target);
        }).on('click', '.tree-container .item-body', function() {
            const $this = $(this);
            
        }).on('click', '.tree-container .item-action-bar .fa-arrow-up,.multiple-container .item-action-bar .fa-arrow-up', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.tree-container,.multiple-container');
            const target = $this.closest('.tree-item,.list-item');
            const prev = target.prev();
            if (prev.length === 0) {
                return;
            }
            const index = target.index();
            const map = box.hasClass('tree-container') ? $(this).parents('.tree-item').map((_, j) => $(j).index()).toArray() : [];
            const data = that.get<IVisualInput>(box.data('cache')).items;
            that.moveItem(data, [index, ...map], [index - 1, ...map]);
            prev.before(target);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.tree-container .item-action-bar .fa-arrow-down,.multiple-container .item-action-bar .fa-arrow-down', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.tree-container,.multiple-container');
            const target = $this.closest('.tree-item,.list-item');
            const next = target.next();
            if (next.length === 0) {
                return;
            }
            const index = target.index();
            const map = box.hasClass('tree-container') ? $(this).parents('.tree-item').map((_, j) => $(j).index()).toArray() : [];
            const data = that.get<IVisualInput>(box.data('cache')).items;
            that.moveItem(data, [index, ...map], [index + 1, ...map]);
            next.after(target);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.tree-container .item-action-bar .fa-edit,.multiple-container .item-action-bar .fa-edit', function(e) {
            e.stopPropagation();
            const target = $(this).closest('.tree-item,.list-item');
            const box = target.closest('.tree-container,.multiple-container');
            box.trigger(EditorEventListEdit, target);
        })
        .on('click', '.tree-container .item-action-bar .fa-plus', function(e) {
            e.stopPropagation();
            const item = $(this).closest('.item-body');
            let next = item.next('.item-children');
            if (next.length === 0) {
                next = $(`<div class="item-children"></div>`);
                item.after(next);
                item.prepend('<div class="item-open-icon"><i class="fa fa-chevron-right"></i></div>');
            }
            const formData = that.get<IVisualInput>(item.closest('.tree-container').data('cache')).input;
            const target = $(that.renderTreeItem(formData));
            next.append(target);
            box.trigger(EditorEventListEdit, target);
        }).on('click', '.tree-container .item-action-bar .fa-trash,.multiple-container .item-action-bar .fa-trash', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.tree-container,.multiple-container');
            const target = $this.closest('.tree-item,.list-item');
            const map = box.hasClass('tree-container') ? [target.index(), ...$(this).parents('.tree-item').map((_, j) => $(j).index()).toArray()] : [target.index()];
            target.remove();
            const data = that.get<IVisualInput>(box.data('cache')).items;
            that.removeItem(data, map);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.tree-container .item-open-icon', function(e) {
            e.stopPropagation();
            $(this).closest('.tree-item').toggleClass('open');
        });
    }
}


