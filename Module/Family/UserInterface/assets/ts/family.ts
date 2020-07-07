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

const FAMILY_SELECT = 'family_select';

function bindEdit() {
    let familyDialog = $('.family-dialog').dialog();
    // $('#parent_id').change(function() {
    //     postJson(BASE_URI + 'family/spouse', {
    //         id: $(this).val()
    //     }, res => {
    //         if (res.code !== 200) {
    //             parseAjax(res);
    //             return;
    //         }
    //         let ele = $('#mother_id');
    //         let old = ele.val();
    //         let html = '<option value="0">请选择</option>';
    //         $.each(res.data, function() {
    //             html += '<option value="' + this.id +'">' + this.name + '</option>';
    //         });
    //         ele.html(html).val(old);
    //     });
    // });
    familyDialog.box.on('click', '*[data-action="add"]', function(e) {
        e.preventDefault();
        familyDialog.find('.dialog-new').show().siblings().hide();
    }).on('click', '*[data-action="back"]', function(e) {
        e.preventDefault();
        familyDialog.find('.dialog-list').show().siblings().hide();
    }).on('submit', '.dialog-list form', function() {
        let form = $(this);
        $.get(form.attr('action'), form.serialize(), res => {
            familyDialog.find('.dialog-result').html(res);
        });
        return false;
    }).on('click', '.dialog-list .dialog-result a', function(e) {
        e.preventDefault();
        $.get($(this).attr('href'), res => {
            familyDialog.find('.dialog-result').html(res);
        });
    }).on('click', '.dialog-list .family-item', function() {
        $(this).addClass('active').siblings().removeClass('active');
    });
    familyDialog.on('done', function() {
        if (this.find('.dialog-new').is(':hidden')) {
            let item = this.find('.family-item.active');
            if (item.length < 1) {
                Dialog.tip('请选择');
                return;
            }
            this.close();
            this.trigger(FAMILY_SELECT, item.attr('data-id'), item.find('.name').text());
            return;
        }
        let form = this.find('.dialog-new form');
        ajaxForm(form.attr('action'), form.serialize(), res => {
            if (res.code !== 200) {
                return;
            }
            this.close();
            this.trigger(FAMILY_SELECT, res.data.id, res.data.name);
        });
    });
    $('.zd-tab').on('click', '*[data-action="family"]', function() {
        let $this = $(this);
        familyDialog.show();
        familyDialog.on(FAMILY_SELECT, function(id, name) {
            $this.find('span').text(name);
            $this.find('input').val(id);
        });
    });
    bindDatePicker('birth_at', 'death_at', 'yyyy-mm-dd hh:ii:ss');
    bindDatePicker();
}