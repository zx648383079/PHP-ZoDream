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
