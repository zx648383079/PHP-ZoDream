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
}
