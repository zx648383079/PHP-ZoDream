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
        const data: any = EditorHtmlHelper.formData(this.box);
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