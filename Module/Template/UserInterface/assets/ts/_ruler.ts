class EditorRuler {

    private offset = 0;
    private gap = 10;
    private scale = 1;
    private foreground = '#333';
    private background = '#ccc';
    private baseWidth = 0;
    private baseX = 0;
    private baseSize = 16;

    private element: JQuery<HTMLCanvasElement>;

    constructor(
        private editor: VisualEditor,
        private horizontal = true,
    ) {
        this.editor.on(EditorEventViewInit, () => {
            this.element = this.editor.find<HTMLCanvasElement>(this.horizontal ? '.h-rule-bar' : '.v-rule-bar');
            this.baseSize = this.horizontal ? this.element.height() : this.element.width();
        }).on(EditorEventResize, (width: number, height: number) => {
            this.refresh();
            this.onAfterViewInit();
        }).on(EditorEventPositionChange, (left: number, top: number, scale: number) => {
            this.offset = this.horizontal ? left : top;
            this.scale = scale / 100;
            this.onRender(this.element[0].getContext('2d'));
        });
    }

    public get barSize() {
        return this.baseSize;
    }

    private get offsetX() {
        return this.barSize - this.offset;
    }

    /**
     * 刷新尺寸
     */
    private refresh() {
        this.baseWidth = this.horizontal ? this.editor.innerWidth : this.editor.innerHeight;
    }

    private onAfterViewInit() {
        if (this.element.length < 1) {
            return;
        }
        const canvas = this.element[0];
        if (this.horizontal) {
            canvas.width = this.baseWidth;
            canvas.height = this.barSize;
        } else {
            canvas.width = this.barSize;
            canvas.height = this.baseWidth;
        }
        this.onRender(canvas.getContext('2d'));
    }

    private createLine(event: MouseEvent): IRuleLine {
        const x = (this.horizontal ? event.clientX : event.clientY) - this.baseX;
        return {
            value: x,
            label: Math.floor((x + this.offsetX) / this.scale),
            horizontal: this.horizontal
        };
    }

    private onRender(drawingContext: CanvasRenderingContext2D) {
        drawingContext.fillStyle = this.background;
        drawingContext.lineWidth = 2;
        const h = this.barSize;
        if (this.horizontal) {
            drawingContext.fillRect(0, 0, this.baseWidth, h);
        } else {
            drawingContext.fillRect(0, 0, h, this.baseWidth);
        }
        const gap = this.gap * this.scale;
        const count = Math.ceil(this.baseWidth / gap);
        const start = Math.ceil(this.offsetX / gap);
        const fontSize = h * .3;
        for (let i = 0; i < count; i++)
        {
            const real = start + i;
            const hasLabel = real % 5 == 0;
            const x = (real * gap - this.offsetX);
            const y = h * (hasLabel ? .6 : .4);
            if (this.horizontal)
            {
                this.drawLine(drawingContext, {x, y: 0}, {x, y}, this.foreground);
                if (hasLabel)
                {
                    this.drawText(drawingContext, (real * this.gap).toString(), fontSize, this.foreground, {x, y});
                }
            } else
            {
                this.drawLine(drawingContext, {x: 0, y: x}, {x: y, y: x}, this.foreground);
                if (hasLabel)
                {
                    this.drawText(drawingContext, (real * this.gap).toString(), fontSize, this.foreground, {x: y - fontSize * 2, y: x});
                }
            }
        }
    }

    private drawText(context: CanvasRenderingContext2D, text: string, font: number, color: string, point: IPoint) {
        context.font = `normal normal bold ${font}px`;
        context.fillStyle = color;
        context.fillText(text, point.x, point.y + font);
    }

    private drawLine(
        context: CanvasRenderingContext2D,
        move: IPoint,
        line: IPoint,
        color: string
    ): void {
        context.beginPath();
        context.strokeStyle = color;
        context.moveTo(move.x, move.y);
        context.lineTo(line.x, line.y);
        context.stroke();
    }

}