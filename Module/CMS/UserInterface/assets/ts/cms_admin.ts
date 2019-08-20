function bindField(baseUri: string) {
    let field = $('#field');
    $('#name').blur(function() {
        pinyinIfEmpty(field, $(this).val());
    });
    $("#type").change(function() {
        $.get(baseUri, {
            id: $("[name=id]").val(),
            type: $(this).val()
        }, function(html) {
            $(".option-box").html(html);
        });
    }).trigger('change');
    $('.add-box button').click(function() {
        let input = $(this).prev(),
            val = input.val().toString().trim();
        if (val.length < 1) {
            return;
        }
        input.val('');
        let box = $(this).closest('.tab-group');
        let num = box.find('.radio-label').length;
        box.find('[name="tab_name"]').removeAttr('checked');
        let addBox = $(this).closest('.add-box');
        addBox.before(`<span class="radio-label">
        <input type="radio" id="tab_name${num}" name="tab_name" value="${val}" checked>
        <label for="tab_name${num}">${val}</label>
    </span>`);
        if (num >= 4) {
            addBox.remove();
        }
    });
}

function bindCat(baseUri: string) {
    let name = $('#name');
    $('#title').blur(function() {
        pinyinIfEmpty(name, $(this).val());
    });
    $.when(
        $.getScript('/assets/ueditor/ueditor.config.js'), 
        $.getScript('/assets/ueditor/ueditor.all.js')).then(function() {
            UE.delEditor('container');
            UE.getEditor('container');
    });
}

function pinyinIfEmpty(ele: JQuery, val: string) {
    if (ele.val() || !val) {
        return;
    }
    postJson('/tool/converter', {
        type: 'pinyin',
        content: val
    }, function(data) {
        if (data.code != 200) {
            return;
        }
        ele.val(data.data.result.replace(/\s/g, '').toLowerCase()).trigger('blur');
    });
}

function bindEditModel() {
    let valIfEmpty = function(ele: JQuery, val: string) {
        if (!ele.val()) {
            ele.val(val);
        }
    },
    table = $('#table').blur(function() {
        let val = $(this).val();
        if (!val) {
            return;
        }
        valIfEmpty($('#category_template'), val + '');
        valIfEmpty($('#list_template'), val + '_list');
        valIfEmpty($('#show_template'), val + '_detail');
    });
    $('#name').blur(function() {
        pinyinIfEmpty(table, $(this).val());
    });
    let type0 = $("#type0").click(function() {
        $(".content-box").hide();
        $(".form-box").show();
    });
    let type1 = $("#type1").click(function() {
        $(".content-box").show();
        $(".form-box").hide();
    });
    if (type0.is(':checked')) {
        type0.trigger('click');
    } else {
        type1.trigger('click');
    }
}
