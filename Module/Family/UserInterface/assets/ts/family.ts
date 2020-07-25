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
    }).on('click', '.spouse-item .fa-times', function() {
        let item = $(this).closest('.spouse-item');
        postJson(BASE_URI + 'family/delete_spouse', {
            id: item.data('id')
        }, res => {
            if (res.code === 200) {
                item.remove();
            }
        });
    }).on('click', '.spouse-add', function() {
        $(this).before(`<div class="spouse-item">
        <div class="input-group">
            <label for="mother_id">配偶</label>
            <div class="" data-action="family">
                <span>【请选择】</span>
                <input type="hidden" name="spouse[spouse_id][]" value="0">
            </div>
        </div>
        <div class="input-group">
<label for="spouse_relation_">关系</label>
<div class="">
<select id="spouse_relation_" name="spouse[relation][]"><option value="0" selected="">妻</option>
</select>

</div>
</div>                    <div class="input-group">
            <label for="start_at">婚姻时间</label>
            <div class="">
                <input type="text" id="start_at" class="form-control " name="spouse[start_at][]">
                ~
                <input type="text" id="end_at" class="form-control " name="spouse[end_at][]">
            </div>
        </div>
    </div>`);
    });
    bindDatePicker('birth_at', 'death_at', 'yyyy-mm-dd hh:ii:ss');
    bindDatePicker();
}