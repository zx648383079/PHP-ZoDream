let autoTimer = 0;
let autoDraft = () => {
    if (autoTimer > 0) {
        return;
    }
    autoTimer = setTimeout(() => {
        if (autoTimer > 0) {
            clearTimeout(autoTimer);
            autoTimer = 0;
        }
        
    }, 3000);
};

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
    $('#open_type').change(function() {
        let val = $(this).val() as number;
        let ruleInput = $('#open_rule');
        let ruleBox = ruleInput.closest('.input-group');
        if (val < 5) {
            ruleInput.val('');
            ruleBox.hide();
            return;
        }
        let ruleLabel = ruleBox.find('label');
        const labelItems = {
            5: '阅读密码',
            6: '阅读价格'
        }
        ruleLabel.text(labelItems[val] || '公开规则');
        ruleBox.show();
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