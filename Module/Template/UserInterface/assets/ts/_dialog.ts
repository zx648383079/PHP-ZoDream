
class EditorDialog {

    private box: DialogBox;
    private target: EditorWeight;
    private innerForm: JQuery<HTMLFormElement>;

    constructor(
        private editor: VisualEditor
    ) {
        const that = this;
        this.editor.on(EditorEventViewInit, () => {
            const target = this.editor.find<HTMLDivElement>('.editor-dialog');
            this.innerForm = target.find<HTMLFormElement>('.dialog-body');
            this.box = (target as any).dialog();
            this.bindEvent();
            this.box.on('done', () => {
                this.editor.emit(EditorEventSaveWeightProperty, this.target.id(), EditorHtmlHelper.formData(target.find('.dialog-body')), data => {
                    that.editor.emit(EditorEventOpenProperty, that.target);
                    that.target.html(data.html);
                });
                this.box.close();
            })
        }).on(EditorEventOpenEditDialog, (weight: EditorWeight) => {
            this.target = weight;
            this.editor.emit(EditorEventWeightForm, weight.id(), data => {
                this.box.find('.dialog-body').html(`<input type="hidden" name="id" value="${weight.id()}">${EditorHtmlHelper.render(data.form)}`);
                this.box.showCenter();
            });
        });
    }

    private bindEvent() {
        EditorHtmlHelper.bindInputEvent(this.box.box as any);
    }

}