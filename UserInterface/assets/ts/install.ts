import { data } from "jquery";

function bindDB() {
    $('input[name="db[database]"]').blur(function() {
        let db = $(this).val().toString().trim();
        postJson(BASE_URI + 'dbs', $('form').serialize(), res => {
            if (res.code === 200) {
                res.message = res.data.indexOf(db) >= 0 ? '数据库不为空，将覆盖已有表' : '数据库不存在，将自动创建';
            }
            parseAjax(res);
        });
    });
    $('.page-footer .btn').click(function() {
        let form = $('form');
        ajaxForm(form.attr('action'), form.serialize(), res => {
            parseAjax(res);
        });
    });
}

function bindModule() {
    $('.module-box input[type="checkbox"]').click(function(this: HTMLInputElement) {
        let next = $(this).next('input');
        if (!this.checked) {
            next.val('');
            return;
        }
        if (next.val().toString().trim().length > 0) {
            return;
        }
        next.val(this.value.toString().toLowerCase().replace('\\', '/'));
    });
    $('.page-footer .btn').click(function() {
        let form = $('form');
        ajaxForm(form.attr('action'), form.serialize(), res => {
            parseAjax(res);
        });
    });
}