
class EditorScrollBar {

    private baseSize = 8;
    private element: JQuery<HTMLDivElement>;
    private innerBar: JQuery<HTMLDivElement>;

    private left = 0;
    private maxLeft = 0;
    private pxScale = 1;

    constructor(
        private editor: VisualEditor,
        private horizontal = true,
    ) {
        this.editor.on(EditorEventViewInit, () => {
            this.element = this.editor.find(this.horizontal ? '.h-scroll-bar' : '.v-scroll-bar');
            this.innerBar = this.element.find<HTMLDivElement>('.inner-bar');
            this.baseSize = this.horizontal ? this.element.height() : this.element.width();
            this.innerBar.on('mousedown', e => {
                let last: IPoint = {x: e.clientX, y: e.clientY};
                this.editor.onMouse(p => {
                    const offset = this.horizontal ? (p.x - last.x) : (p.y - last.y);
                    this.scroll(this.left + offset);
                    last = p;
                });
            });
        }).on(EditorEventBrowserResize, (left, top, width, height, maxWidth, maxHeight) => {
            if (this.horizontal) {
                this.onRender(left, maxWidth, width, this.element.width());
            } else {
                this.onRender(top, maxHeight, height, this.element.height());
            }
        });
    }

    public get barSize() {
        return this.baseSize;
    }

    /**
     * 移动多少个像素
     * @param offset 
     */
    public move(offset: number) {
        this.scroll(offset / this.pxScale);
    }

    /**
     * 滚动条滑动到多少距离, 不是实际像素
     * @param offset 
     */
    public scroll(offset: number) {
        this.left = Math.max(0, Math.min(offset, this.maxLeft));
        this.innerBar.css(this.horizontal ? 'left' : 'top', this.left + 'px');
        this.output();
    }

    /**
     * 移动百分比
     * @param percent /100 
     */
    public scrollTo(percent: number) {
        this.scroll(percent * this.maxLeft / 100);
    }

    private output() {
        this.editor.emit(EditorEventScroll, this.converter(this.left), this.horizontal);
    }

    /**
     * 滚动条滑动多少距离，转换成实际页面移动多少像素
     * @param offset 
     */
    private converter(offset: number): number {
        return offset * this.pxScale;
    }

    /**
     * 
     * @param viewSize 可见区域的
     * @param pageSize 总页面的
     * @param barSize 滚动条的长度
     */
    private onRender(offsetPx: number, viewSize: number, pageSize: number, barSize: number) {
        if (pageSize <= viewSize) {
            this.maxLeft = 0;
            this.applySize(0, barSize);
            return;
        }
        const minInnerSize = Math.min(6 * this.baseSize, this.baseSize / 2);
        const diff = pageSize - viewSize;
        const barDiff = barSize - diff;
        const innerSize = barDiff <= minInnerSize ? minInnerSize : barDiff;
        this.maxLeft = barSize - innerSize;
        this.pxScale = diff / this.maxLeft;
        this.applySize(offsetPx / this.pxScale, innerSize);
        
    }

    private applySize(offset: number, size: number) {
        this.left = Math.max(0, Math.min(offset, this.maxLeft));
        this.innerBar.css({
            [this.horizontal ? 'left' : 'top']: offset + 'px',
            [this.horizontal ? 'width' : 'height']: size + 'px',
        });
    }
}