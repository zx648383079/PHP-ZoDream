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
            html += `<div class="tree-item">
            <div class="item-body">
                ${iconArrow}
                <div class="item-icon">
                    <i class="fa fa-chain"></i>
                </div>
                <input type="text" class="item-title" value="${item.title}" placeholder="ID: ${item.weight_id}">
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