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