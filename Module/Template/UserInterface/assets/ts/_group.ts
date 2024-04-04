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