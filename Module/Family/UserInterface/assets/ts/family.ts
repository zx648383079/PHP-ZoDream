function bindDatePicker(start: string = 'start_at', end: string = 'end_at', format: string = 'yyyy-mm-dd') {
    let startBox = $('[name='+ start +']').datetimer({
        format
    });
    if (end) {
        $('[name='+ end +']').datetimer({
            format,
            min: startBox
        });
    }
}

function bindEdit() {
    let familyDialog = $('.family-dialog').dialog();
    $('#parent_id').change(function() {
        postJson(BASE_URI + 'family/spouse', {
            id: $(this).val()
        }, res => {
            if (res.code !== 200) {
                parseAjax(res);
                return;
            }
            let ele = $('#mother_id');
            let old = ele.val();
            let html = '<option value="0">请选择</option>';
            $.each(res.data, function() {
                html += '<option value="' + this.id +'">' + this.name + '</option>';
            });
            ele.html(html).val(old);
        });
    });
    familyDialog.find('.dialog-header .fa-plus').click(function() {
        familyDialog.find('.dialog-new').show().siblings().hide();
    });
    bindDatePicker('birth_at', 'death_at', 'yyyy-mm-dd hh:ii:ss');
    bindDatePicker();
}