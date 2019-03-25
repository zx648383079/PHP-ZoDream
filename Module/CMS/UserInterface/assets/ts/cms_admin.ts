function bindField(baseUri: string) {
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
        ele.val(data.data.result.replace(/\s/g, '')).trigger('blur');
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
        valIfEmpty($('#category_template'), val + '.html');
        valIfEmpty($('#list_template'), val + '_list.html');
        valIfEmpty($('#show_template'), val + '_detail.html');
    });
    $('#name').blur(function() {
        pinyinIfEmpty(table, $(this).val());
    });
}
