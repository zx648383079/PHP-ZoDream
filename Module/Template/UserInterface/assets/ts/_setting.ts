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