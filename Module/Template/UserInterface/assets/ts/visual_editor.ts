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
const EditorMobileStyle = 'mobile-style';

type FailureCallbackFunc = (message: string, code?: number) => void;

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
    show();
    hide(); 
}

interface IPageModel {
    id: string;
    name: string;
    title: string;
    edit_url: string;
    preview_url: string;
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
    private ruleLinePanel: JQuery<HTMLDivElement>;
    private browser = new EditorBrowser(this);
    private workspace: JQuery<HTMLDivElement>;
    private shellTimeBar: JQuery<HTMLDivElement>;
    private panelGroup = new EditorPanelGroup(this);
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

    public on(event: string, cb?: Function);
    public on(event: string, cb?: (success: Function, failure: FailureCallbackFunc) => void);
    public on(event: string, cb?: (data: any, success: Function, failure: FailureCallbackFunc) => void);
    public on(event: string, cb?: Function) {
        if (!Object.prototype.hasOwnProperty.call(this.listeners, event)) {
            this.listeners[event] = [];
        }
        this.listeners[event].push(cb);
        return this;
    }

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
        this.box.addClass(EditorMobileStyle);
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
        this.box.removeClass(EditorMobileStyle);
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
            this.browser.navigate(data.edit_url);
        }, message => {

        });
    }

    private bindEvent() {
        const $window = $(window);
        $window.on('resize', () => {
            const top = this.box.offset().top;
            const height = $window.height() - top - 20;
            this.outerHeight = height;
            if (!this.viewInited) {
                return;
            }
            this.emit(EditorEventResize, this.innerWidth, this.innerHeight);
        });
        $(document).on('keydown', e => {
            if (e.ctrlKey) {
                if (e.code == 'KeyS') {
                    e.preventDefault();
                    this.emit(EditorEventSavePage, this.serialize());
                }
            }
        }).on('paste', (e: any) => {
            if (e.clipboardData || e.originalEvent) {
                const clipboardData = (e.clipboardData || (window as any).clipboardData);
                const val = clipboardData.getData('text');
            }
        }).on('mousemove', e => {
            if (this.mouseListener.move) {
                this.mouseListener.move({x: e.clientX, y: e.clientY});
            }
        }).on('mouseup', e => {
            if (this.mouseListener.finish) {
                this.mouseListener.finish({x: e.clientX, y: e.clientY});
            }
        });
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
        });
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
            this.shellTimeBar.text([now.getHours(), now.getMinutes()].map(EditorHelper.twoPad).join(':'));
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
        <div class="dialog dialog-box editor-dialog" data-type="dialog" >
            <div class="dialog-header">
                <div class="dialog-title">编辑</div>
                <i class="fa fa-close dialog-close"></i>
            </div>
            <form class="dialog-body form-table custom-config-view">
                
            </form>
            <div class="dialog-footer">
                <button type="button" class="dialog-yes">确认</button>
                <button type="button" class="dialog-close">取消</button>
            </div>
        </div>
        `);
        this.emit(EditorEventViewInit);
        this.workspace = this.find<HTMLDivElement>('.editor-container');
        this.ruleLinePanel = this.find<HTMLDivElement>('.rule-line-bar');
        this.shellTimeBar = this.find<HTMLDivElement>('.shell-bar .time');
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

class EditorPanelGroup {

    private children: IEditorPanel[] = [
        new EditorWeightPanel(this.editor),
        new EditorPropertyPanel(this.editor),
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
            const maxHeight = this.editor.outerHeight;
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

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventGetStyleSuccess, data => {
            this.renderStyle(data);
        }).on(EditorEventOpenProperty, (weight: EditorWeight) => {
            this.box.find<HTMLDivElement>('.panel-header').trigger('click');
            this.target = weight;
            this.editor.emit(EditorEventGetWeightProperty, weight.id(), data => {
                this.applyForm(data);
            });
        });
        // const EditorEventOpenProperty = 'editor_open_property';
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
                    <div class="tab-body form-table">
                        <div class="tab-item active">
                        </div>
                        <div class="tab-item">
                        <div class="expand-box open">
                                <div class="expand-header">整体<span class="fa fa-chevron-down"></span></div>
                                <div class="expand-body">
                                    
                                </div>
                            </div>
                            <div class="expand-box">
                                <div class="expand-header">标题<span class="fa fa-chevron-down"></span></div>
                                <div class="expand-body">
                                    
                                </div>
                            </div>
                            <div class="expand-box">
                                <div class="expand-header">内容<span class="fa fa-chevron-down"></span></div>
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
        this.box.find('.style-panel').append(html);
    }

    public show() {
        this.box.removeClass('min');
    }
    public hide() {
        this.box.addClass('min')
    }

    private applyForm(data: any) {
        const styles = data.settings?.style;
        let items = this.box.find('.form-table .tab-item');
        items[0].innerHTML = EditorHtmlHelper.title(data.title) + EditorHtmlHelper.lazy(data.settings?.lazy);
        let boxes = $(items[1]).find('.expand-body');
        boxes[0].innerHTML = EditorHtmlHelper.margin(styles?.margin)
                        + EditorHtmlHelper.position(styles?.position)
                        + EditorHtmlHelper.border(null, styles?.border) 
                        + EditorHtmlHelper.radius(null, data.settings ? data.settings['border-radius'] : null)
                        + EditorHtmlHelper.color(null, data.settings?.color)
                        + EditorHtmlHelper.background(null, data.settings?.background);
        boxes[1].innerHTML = EditorHtmlHelper.visibility('title', styles?.title?.visibility)
                        + EditorHtmlHelper.padding('title', styles?.title?.padding)
                        + EditorHtmlHelper.border('title', styles?.title?.border) 
                        + EditorHtmlHelper.radius('title', styles?.title['border-radius'])
                        + EditorHtmlHelper.color('title', styles?.title?.color)
                        + EditorHtmlHelper.fontSize('title', styles?.title['font-size'])
                        + EditorHtmlHelper.fontWeight('title', styles?.title['font-weight'])
                        + EditorHtmlHelper.textAlign('title', styles?.title['text-align'])
                        + EditorHtmlHelper.background('title', styles?.title?.background);
        boxes[2].innerHTML = EditorHtmlHelper.visibility('content', styles?.content?.visibility)
                        + EditorHtmlHelper.padding('content', styles?.content?.padding)
                        + EditorHtmlHelper.border('content', styles?.content?.border) 
                        + EditorHtmlHelper.radius('content', styles?.content['border-radius'])
                        + EditorHtmlHelper.color('content', styles?.content?.color)
                        + EditorHtmlHelper.fontSize('content', styles?.content['font-size'])
                        + EditorHtmlHelper.fontWeight('content', styles?.content['font-weight'])
                        + EditorHtmlHelper.textAlign('content', styles?.content['text-align'])
                        + EditorHtmlHelper.background('content', styles?.content?.background);
        this.box.find('.style-item').each(function() {
            let $this = $(this);
            $this.toggleClass('active', $this.attr('data-id') == data.theme_style_id);
        });
    }
}

class EditorWeightPanel implements IEditorPanel {
    private box: JQuery<HTMLDivElement>;

    constructor(
        private editor: VisualEditor
    ) {
        const that = this.editor;
        this.editor.on(EditorEventAfterViewInit, () => {
            this.editor.emit(EditorEventGetWeights, data => {
                this.renderWeight(data.weights);
                this.editor.emit(EditorEventGetStyleSuccess, data.styles);
                this.box.find('.weight-edit-grid').attr('draggable', 'true').on('dragstart', function(e) {
                    e.originalEvent.dataTransfer.setData("Text", e.target.id);
                    that.emit(EditorEventDragStart, $(this));
                });
            });
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
                return `<div class="weight-edit-grid" data-type="weight" data-weight="${item.id}" data-group="${group.id}">
                <div class="weight-preview">
                    <div class="thumb">
                        <img src="${item.thumb}" alt="${item.name}" title="${item.description}">
                    </div>
                    <p class="title">${item.name}</p>
                </div>
                <div class="weight-action">
                    <a class="refresh">刷新</a>
                    ${editable}
                    <a class="property">属性</a>
                    <a class="drag">拖拽</a>
                    <a class="del">删除</a>
                </div>
                <div class="weight-view">
                    <img src="/assets/images/ajax.gif" alt="">
                </div>
            </div>`;
            });
            return `
            <ul class="menu">
                <li class="expand-box open">
                    <div class="expand-header">
                        ${group.name}
                        <span class="fa fa-chevron-down"></span>
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

class EditorDialog {

    private box: DialogBox;
    private target: EditorWeight;

    constructor(
        private editor: VisualEditor
    ) {
        const that = this;
        this.editor.on(EditorEventViewInit, () => {
            const target = this.editor.find<HTMLDivElement>('.editor-dialog');
            this.box = (target as any).dialog();
            target.on('click', '.multiple-box .multiple-add-btn', function() {
                let $this = $(this);
                $this.before($this.prev('.item').clone());
            }).on('click', '.multiple-box .multiple-del-btn', function() {
                let $this = $(this);
                if ($this.closest('.multiple-box').find('.item').length > 1) {
                    $this.closest('.item').remove();
                }
            });
            this.box.on('done', () => {
                this.editor.emit(EditorEventSaveWeightProperty, target.find('.dialog-body').serialize(), data => {
                    that.editor.emit(EditorEventOpenProperty, that.target);
                    that.target.html(data.html);
                });
                this.box.close();
            })
        }).on(EditorEventOpenEditDialog, (weight: EditorWeight) => {
            this.target = weight;
            this.editor.emit(EditorEventWeightForm, weight.id(), data => {
                this.box.find('.dialog-body').html('<input type="hidden" name="id" value="'+ weight.id() +'">' + data.html);
                this.box.showCenter();
            });
        });
    }
}

class EditorBrowser {
    private shell: JQuery<HTMLDivElement>;
    private frame: JQuery<HTMLIFrameElement>;
    private frameBody: JQuery<HTMLBodyElement>;
    private dragWeight: JQuery<HTMLDivElement>;
    private selectedWeight: EditorWeight;
    private bound: IBound;
    private frameScale = 100;

    constructor(
        private editor: VisualEditor,
    ) {
        this.editor.on(EditorEventViewInit, () => {
            this.shell = this.editor.find<HTMLDivElement>('.work-container');
            this.frame = this.editor.find<HTMLIFrameElement>('.panel-main');
            this.bindEvent();
        }).on(EditorEventScroll, (offset: number, horizontal: boolean) => {
            if (horizontal) {
                this.bound.left = -offset;
            } else {
                this.bound.top = -offset;
            }
            this.shell.css('transform', `translate(${this.bound.left}px, ${this.bound.top}px)`);
            this.editor.emit(EditorEventPositionChange, this.bound.left, this.bound.top, this.frameScale);
        }).on(EditorEventResize, () => {
            if (!this.bound) {
                return;
            }
            this.resize(this.bound.width, this.bound.height);
        }).on(EditorEventDragStart, (weight: JQuery<HTMLDivElement>) => {
            this.dragWeight = weight;
        });
    }

    private bindEvent() {
        this.frame.on('load', () => {
            this.frameBody = this.frame.contents().find<HTMLBodyElement>('body');
            this.editor.emit(EditorEventBrowserReady);
            this.bindFrameEvent();
        });
    }

    private bindFrameEvent() {
        const that = this;
        this.frameBody.on('dragover', '.weight-row', (e) => {
            e.stopPropagation();
            e.preventDefault();
        }).on('drop', '.weight-row', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const row = $(this);
            if (that.dragWeight) {
                that.selectedWeight = new EditorWeight(that.editor, that.dragWeight.clone());
                that.selectedWeight.appendTo(row);
                return;
            }
            if (that.selectedWeight) {
                that.selectedWeight.moveTo(row);
            }
        }).on('dragover', '.weight-edit-grid', (e) => {
            e.stopPropagation();
            e.preventDefault();
        }).on('drop', '.weight-edit-grid', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const row = $(this);
            if (that.dragWeight) {
                that.selectedWeight = new EditorWeight(that.editor, that.dragWeight.clone());
                that.selectedWeight.appendTo(row.closest('.weight-row'), row);
                return;
            }
            if (that.selectedWeight) {
                that.selectedWeight.moveTo(row.closest('.weight-row'), row);
            }
        }).on('dragstart', '.weight-edit-grid', function(e) {
            e.originalEvent.dataTransfer.setData("Text", e.target.id);
            that.selectedWeight = new EditorWeight(that.editor, $(this));
        }).on('dragend', '.weight-edit-grid', function() {
            $(this).attr('draggable', 'false');
        }).on('mousedown', '.weight-action .drag', function(e: any) {
            e.stopPropagation();
            that.dragWeight = undefined;
            const weight = $(this).closest('.weight-edit-grid');
            that.selectedWeight = new EditorWeight(that.editor, weight);
            $(this).attr('draggable', 'true');
        }).on('click', '.weight-action .del', function(e) {
            e.stopPropagation();
            that.selectedWeight = new EditorWeight(that.editor, $(this).closest('.weight-edit-grid'));
            that.selectedWeight.tapRemove();
        }).on('click', '.weight-action .edit', function(e) {
            e.stopPropagation();
            that.selectedWeight = new EditorWeight(that.editor, $(this).closest('.weight-edit-grid'));
            that.selectedWeight.tapEdit();
        }).on('click', '.weight-action .refresh', function(e) {
            e.stopPropagation();
            that.selectedWeight = new EditorWeight(that.editor, $(this).closest('.weight-edit-grid'));
            that.selectedWeight.tapRefresh();
        }).on('click', '.weight-action .property', function(e) {
            e.stopPropagation();
            that.selectedWeight = new EditorWeight(that.editor, $(this).closest('.weight-edit-grid'));
            that.selectedWeight.tapProperty();
        }).on('click', 'a', function(e) {
            e.preventDefault();
        });
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
        this.shell.css('transform', 'scale(' + (val / 100) +')');
        this.editor.emit(EditorEventPositionChange, this.bound.left, this.bound.top, this.frameScale);
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
            this.shell.css('transform', 'scale(' + (scale / 100) +')');
        }
        this.editor.emit(EditorEventBrowserResize, left, top, width, height, maxWidth, maxHeight);
        this.editor.emit(EditorEventPositionChange, left, top, this.frameScale);
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
        this.frameBody.find<HTMLDivElement>('.weight-row').each(function() {
            const row = $(this);
            const parent_id = row.attr('data-id');
            const parent_index = row.attr('data-index');
            row.children('.weight-edit-grid').each(function(index) {
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
        this.box.toggleClass('weight-edit-mode', state);
        return this;
    }

    public toggleLoading(state?: boolean) {
        this.box.toggleClass('weight-loading', state);
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
        this.baseWidth = this.horizontal ? this.editor.innerWidth : (this.editor.innerHeight + this.barSize);
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
}

class EditorHtmlHelper {
    private static _guid = 0;
    public static guid(): number {
        return EditorHtmlHelper._guid ++;
    }

    public static mapJoinHtml(data: object, cb: (val: any, key?: string|number) => string): string {
        let html = '';
        EditorHelper.eachObject(data, (val, key) => {
            html += cb(val, key);
        });
        return html;
    }

    public static title(val: string = '') {
        const id = 'title_' + EditorHtmlHelper.guid();
        return EditorHtmlHelper.input(id, '标题', EditorHtmlHelper.text(id, 'title', val));
    }

    public static lazy(val: number) {
        const id = 'settings_lazy_' + EditorHtmlHelper.guid();
        return EditorHtmlHelper.input(id, '懒加载', EditorHtmlHelper.radio(id, 'settings[lazy]', ['关闭', '开启'], !val ? 0 : val));
    }

    

    public static margin(vals: string[] = []) {
        return EditorHtmlHelper.sideInput('settings[style][margin]', '外边距', vals);
    }

    public static padding(name: string, vals: string[] = []) {
        return EditorHtmlHelper.sideInput('settings[style][' + name +'][padding]', '内边距', vals);
    }

    public static position(data: any) {
        const html = EditorHtmlHelper.positionSide(data?.type, data?.value);
        const option = EditorHtmlHelper.option({
            static: '无',
            relative: '相对定位',
            absolute: '绝对定位',
            fixed: '固定定位'
        }, data?.type);
        return EditorHtmlHelper.input('', '悬浮', `<select name="settings[style][position][type]">${option}</select><div class="side-input">${html}</div>`, 'position-input');
    }

    public static positionSide(type: string, value?: any) {
        return type && type != 'static' ? EditorHtmlHelper.side('settings[style][position][value]', 'settings[style][position][value]', value) : '';
    }

    public static border(name?: string, data?: any) {
        name = 'settings[style]'+ (name ? '[' + name +']' : '') +'[border]';
        const id = EditorHtmlHelper.nameToId(name) + '_' + EditorHtmlHelper.guid();
        let html = EditorHtmlHelper.checkbox(id, name + '[side][]', ['上', '右', '下', '左'], data?.side)
        const option = EditorHtmlHelper.option(['实线', '虚线'], data && data.value && data.value[1] == 1 ? 1 : 0)
        return EditorHtmlHelper.input(id, '边框', `<input type="text" class="form-control" name="${name}[value][]" value="${data?.value[0]}" placeholder="粗细" size="4"><select name="${name}[value][]">${option}</select><input type="color" name="${name}[value][]" value="${data?.value[2]}"><div class="side-input">${html}</div>
        `);
    }

    public static radius(name?: string, data?: string[]) {
        name = 'settings[style]'+ (name ? '[' + name +']' : '') +'[border-radius]';
        const id = EditorHtmlHelper.nameToId(name) + '_' + EditorHtmlHelper.guid();
        return EditorHtmlHelper.input(id, '圆角', `<input type="text" id="${id}_0" class="form-control " name="${name}[]" value="" size="4" placeholder="左上">
        <input type="text" id="${id}_1" class="form-control " name="${name}[]" value="" size="4" placeholder="右上">
        <br/>
        <input type="text" id="${id}_2" class="form-control " name="${name}[]" value="" size="4" placeholder="左下">
        <input type="text" id="${id}_3" class="form-control " name="${name}[]" value="" size="4" placeholder="右下">`);
    }


    public static color(name?: string, data?: any) {
        name = 'settings[style]'+ (name ? '[' + name +']' : '') +'[color]';
        const id = EditorHtmlHelper.nameToId(name) + '_' + EditorHtmlHelper.guid();
        return EditorHtmlHelper.input(id, '字体颜色', EditorHtmlHelper.radio(id, name + '[type]', ['无', '有'], data?.type) + `<input type="color" name="${name}[value]" value="${data?.value}">`);
    }

    public static background(name?: string, data?: any) {
        name = 'settings[style]'+ (name ? '[' + name +']' : '') +'[background]';
        const id = EditorHtmlHelper.nameToId(name) + '_' + EditorHtmlHelper.guid();
        let html = EditorHtmlHelper.radio(id, name+ '[type]', ['无', '颜色', '图片'], '0') + '<div class="value-input">' + EditorHtmlHelper.backgroundValue(name, data?.type)+'</div>';
        return EditorHtmlHelper.input(id, '背景', html, 'background-input');
    }

    public static backgroundValue(name: string, type?: number) {
        if (!type || type < 1) {
            return '';
        }
        if (type == 2) {
            return `<div class="file-input">
            <input type="text" class="form-control " name="${name}[value]" value="" size="10">
            <button type="button" data-type="upload">上传</button>
        </div>`;
        };
        return `<input type="color" name="${name}[value]">`;
    }

    public static visibility(name: string, val: string|number) {
        name = 'settings[style]['+ name +'][visibility]';
        const id = EditorHtmlHelper.nameToId(name) + '_' + EditorHtmlHelper.guid();
        
        return EditorHtmlHelper.input(id, '可见', EditorHtmlHelper.radio(id, name, ['显示', '隐藏'], val));
    }

    public static fontSize(name: string, val?: string) {
        name = 'settings[style]['+ name +'][font-size]';
        const id = EditorHtmlHelper.nameToId(name) + '_' + EditorHtmlHelper.guid();
        
        return EditorHtmlHelper.input(id, '字体大小', EditorHtmlHelper.text(id, name, val, 4));
    }

    public static textAlign(name: string, val?: string) {
        name = 'settings[style]['+ name +'][text-align]';
        const id = EditorHtmlHelper.nameToId(name) + '_' + EditorHtmlHelper.guid();
        
        return EditorHtmlHelper.input(id, '字体位置', EditorHtmlHelper.radio(id, name, ['居左', '居中', '居右'], !val ? 0 : val));
    }

    public static fontWeight(name: string, val?: string) {
        name = 'settings[style]['+ name +'][font-weight]';
        const id = EditorHtmlHelper.nameToId(name) + '_' + EditorHtmlHelper.guid();
        
        return EditorHtmlHelper.input(id, '字体粗细', EditorHtmlHelper.text(id, name, val, 4));
    }



    private static option(items: any, selected: string| number) {
        let html = '';
        $.each(items, function(i: string) {
            const sld = selected == i ? ' selected' : '';
            html += `<option value="${i}"${sld}>${this}</option>`;
        });
        return html;
    }

    private static sideInput(name: string, label: string, vals: string[] = []) {
        const id = EditorHtmlHelper.nameToId(name) + '_' + EditorHtmlHelper.guid();
        return EditorHtmlHelper.input(id, label, EditorHtmlHelper.side(id, name, vals), 'side-input');
    }

    private static side(id: string, name: string, vals: string[] = []) {
        let html = '';
        $.each(['上', '右', '下', '左'], function(i: number) {
            const val = vals && vals.length > i ? vals[i] : '';
            html += `<input type="text" id="${id}_${i}" class="form-control " name="${name}[]" size="4" value="${val}" placeholder="${this}">`;
        });
        return html;
    }

    private static radio(id: string, name: string, items: any, selected: string| number) {
        let html = '';
        let j = 0;
        $.each(items, function(i: string) {
            const index = [id, j ++].join('_');
            const chk = i == selected ? ' checked' : '';
            html += `<span class="radio-label"><input type="radio" id="${index}" name="${name}" value="${i}"${chk}><label for="${index}">${this}</label></span>`;
        });
        return html;
    }

    private static checkbox(id: string, name: string, items: any, val: string[] = []) {
        let html = '';
        let j = 0;
        $.each(items, function(i: string) {
            const index = [id, j ++].join('_');
            const chk = val && val.indexOf(i) >= 0 ? ' checked' : '';
            html += `<span class="check-label"><input type="checkbox" id="${index}" name="${name}" value="${i}"${chk}><label for="${index}">${this}</label></span>`;
        });
        return html;
    }

    private static text(id: string, name: string, val: string = '', size?: number) {
        const option = size ? ` size="${size}"` : '';
        if (!val) {
            val = '';
        }
        return `<input type="text" id="${id}" class="form-control" name="${name}" value="${val}"${option}>`;
    }

    private static input(id: string, name: string, content: string, cls: string = ''): string {
        return `<div class="input-group"><label for="${id}">${name}</label><div class="${cls}">${content}</div></div>`;
    }

    private static nameToId(name: string) {
        return name.replace(/\[/g, '_').replace(/\]/g, '');
    }
}