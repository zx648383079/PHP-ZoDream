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