/// <reference types="jquery" />

interface JQuery<TElement = HTMLElement> {
    editor(option?: ZreEditor.IEditorOption): ZreEditor.EditorApp;
    dialog(option?: ZreDialog.DialogOption): ZreDialog.DialogBox;
}