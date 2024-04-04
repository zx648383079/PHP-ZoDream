class EditorToolBar {

    private scaleBar: JQuery<HTMLSpanElement>;
    private scaleValue = 100;

    constructor(
        private editor: VisualEditor
    ) {
        this.editor.on(EditorEventViewInit, () => {
            const box = this.editor.find<HTMLDivElement>('.panel-tool-bar');
            this.scaleBar = box.find<HTMLSpanElement>('.scale-text');
            this.bindEvent(box);
        }).on(EditorEventPositionChange, (left: number, top: number, scale: number) => {
            this.scaleValue = scale;
            this.scaleBar.text(scale + '%');
        });
    }

    private bindEvent(box: JQuery<HTMLDivElement>) {
        box.on('click', '.fa-minus-circle', () => {
            if (this.scaleValue < 30) {
                return;
            }
            this.editor.scale(this.scaleValue - 10);
        }).on('click', '.fa-plus-circle', () => {
            if (this.scaleValue > 300) {
                return;
            }
            this.editor.scale(this.scaleValue + 10);
        }).on('click', '.fa-undo', () => {
            this.editor.rotate();
        }).on('click', '.fa-expand', () => {
            this.editor.normal();
        }).on('click', '.fa-expand-arrows-alt', () => {
            this.editor.reset();
        });
    }
}