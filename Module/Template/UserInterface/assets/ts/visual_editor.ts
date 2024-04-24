// @import '_types.ts'
// @import '_toolbar.ts'
// @import '_soul.ts'
// @import '_group.ts'
// @import '_property.ts'
// @import '_browser.ts'
// @import '_dialog.ts'
// @import '_group.ts'
// @import '_helper.ts'
// @import '_input.ts'
// @import '_input_element.ts'
// @import '_input_base.ts'
// @import '_input_popup.ts'
// @import '_input_list.ts'
// @import '_input_other.ts'
// @import '_layer.ts'
// @import '_page.ts'
// @import '_ruler.ts'
// @import '_scrollbar.ts'
// @import '_setting.ts'
// @import '_weight.ts'


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

