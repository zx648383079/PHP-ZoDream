function bindEdit(configs: any) {
    let editor: any,
        box = $('#editor-container');
    UE.delEditor('editor-container');
    $('select[name=type]').change(function () { 
        $('#source_url').closest('.input-group').toggle($(this).val() == 1);
    }).trigger('change');
    $("#edit_type").change(function() {
        if ($(this).val() == 1) {
            if (editor) {
                editor.destroy();
                box.css({
                    width: '100%'
                });
            }
            return;
        }
        editor = UE.getEditor('editor-container', configs);
    }).trigger('change');
}