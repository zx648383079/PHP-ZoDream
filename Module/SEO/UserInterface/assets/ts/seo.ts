function bindSetting() {
    $('.tab-header .tab-item').eq(0).trigger('click');
    $("#field_type,#type").on('change', function() {
        $(this).closest('.input-group').next('.group-property').toggle($(this).val() != 'group');
    }).trigger('change');
    let dialog = $('.option-dialog').dialog();
    $('.option-box .input-group .fa-edit').on('click',function() {
        $.getJSON(BASE_URI + 'setting/info', {id: $(this).data('id')}, function(data) {
            if (data.code !== 200) {
                return;
            }
            $.each(data.data, function(i) {
                dialog.find('*[name="'+ i+'"]').val(this + '').trigger('change');
            });
            dialog.show();
        });
        
    });
    dialog.on('done', function() {
        ajaxForm(BASE_URI + 'setting/update', dialog.find('form').serialize());
    });
    dialog.find('.dialog-del').on('click',function() {
        postJson(BASE_URI + 'setting/delete', {id: dialog.find('[name=id]').val()}, function(data) {
            parseAjax(data);
        });
    });
    $('[data-action="edit"]').on('click',function() {
        $(this).closest('form').toggleClass('edit-mode');
    });
}

function renderCMD(lines: string[]) {
    let i = 0,
        box = $('.cmd-box'),
        handle = setInterval(function() {
            if (i >= lines.length) {
                clearInterval(handle);
                return;
            }
            box.append('<p>'+ lines[i++] +'</p>').scrollTop(box[0].scrollHeight);
        }, Math.max(16, Math.floor(Math.min(lines.length * 100, 10000) / lines.length)));
}