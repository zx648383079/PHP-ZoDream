class EditorSettingPanel implements IEditorPanel, IEditorInputGroup {
    private box: JQuery<HTMLDivElement>;
    private lastAt: number = 0;
    private booted = false;
    private isAsync = false;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventAfterViewInit, () => {
            this.bindEvent();
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

    private saveSync() {
        this.isAsync = false;
        const data: any = EditorHtmlHelper.formData(this.box);
        this.editor.emit(EditorEventSavePageSetting, data as Object, res => {
            
        });
    }

    private autoSave() {
        this.isAsync = true;
        this.lastAt = new Date().getTime();
    }

    public notify(control: IEditorInput): void {
        this.autoSave();
    }

    private bindEvent() {
        EditorHtmlHelper.bindInputEvent(this.box, this);
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
        if (this.booted) {
            return;
        }
        this.booted = true;
        this.editor.emit(EditorEventPageSetting, data => {
            this.applyForm(data);
        });
    }
    public hide() {
        this.box.addClass('min')
    }


    private applyForm(data: any) {
        const items = this.box.find('.tab-body .tab-item');
        items[0].innerHTML = EditorHtmlHelper.render(data.page);
        items[1].innerHTML = EditorHtmlHelper.render(data.theme);
        EditorHtmlHelper.readyControl(items, this);
    }

}