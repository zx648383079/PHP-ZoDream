class EditorWeightSoul {
    private box: JQuery<HTMLDivElement>;
    private target: JQuery<HTMLDivElement>;
    private isNew = false;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventViewInit, () => {
            this.box = this.editor.find<HTMLDivElement>('.await-widget-box');
        }).on(EditorEventDrag, (element: JQuery<HTMLDivElement>, isNew: boolean, mousePos: IPoint) => {
            const offset = element.offset();
            const mouseInPos = {
                x: mousePos.x - offset.left,
                y: mousePos.y - offset.top
            };
            const width = element.width();
            const height = element.height();
            this.target = element;
            this.isNew = isNew;
            this.box.html(element.prop('outerHTML'));
            this.editor.onMouse(p => {
                if (!this.target) {
                    return;
                }
                this.setBound(p.x - mouseInPos.x, p.y - mouseInPos.y, width, height);
            }, p => {
                if (!this.target) {
                    return;
                }
                const items = this.editor.browser.getElementByPoint(p);
                if (!items || !items[0]) {
                    this.reset();
                    return;
                }
                // 在手机端 touchend 事件出现问题无法被部件捕捉到
                this.editor.emit(EditorEventDrog, ...items);
            });
        }).on(EditorEventDrog, (row: JQuery<HTMLDivElement>, replace?: JQuery<HTMLDivElement>) => {
            if (!this.target) {
                return;
            }
            this.editor.emit(EditorEventOperateWeight, this.isNew, this.target, row, replace);
            this.reset();
        });
    }

    private setBound(x: number, y: number, width: number, height: number) {
        this.box.css({
            width: width + 'px',
            height: height + 'px',
            left: x + 'px',
            top: y + 'px'
        });
    }

    private reset() {
        if (!this.target) {
            return;
        }
        this.target = undefined;
        this.setBound(0, 0, 0, 0);
    }
}