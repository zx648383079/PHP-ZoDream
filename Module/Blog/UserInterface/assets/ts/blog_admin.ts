function bindEdit(configs: any, tags = []) {
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
    $("#tag-box").select2({
        ajax: {
            url: BASE_URI + 'tag',
            data: function (params) {
                return {
                    keywords: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
              data = data.data;
              return {
                results: data.data.map(item => {
                    return {
                        id: item.id,
                        text: item.name,
                        selected: tags.indexOf(item.id) >= 0
                    }
                }),
                pagination: {
                    more: data.paging.more
                }
              };
            }
          }
    });
}