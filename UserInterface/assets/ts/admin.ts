/**
 * 绑定多选列表
 * @param doc 
 */
function bindMultipleTable(doc: JQuery<Document>) {
    const updateCount = (parent: JQuery<HTMLDivElement>) => {
        const target = parent.find('.page-multiple-count');
        const items = parent.find('.page-multiple-td .checkbox.checked')
        target.text(items.length);
        const link = target.closest('a');
        const idItems = [];
        items.each(function() {
            idItems.push($(this).parent().data('id'));
        });
        link.attr('href', link.attr('href').replace(/id=[\d,]*/, 'id=' + idItems.join(',')));
        link.data('tip', `确定要删除选中的 ${items.length} 条数据？`);
    };
    doc.on('click', '.page-multiple-table .page-multiple-toggle', function(e) {
        e.preventDefault();
        $(this).closest('.page-multiple-table').toggleClass('page-multiple-enable');
    }).on('click', '.page-multiple-table .page-multiple-td .checkbox', function() {
        const $this = $(this);
        const parent = $this.closest('.page-multiple-table');
        const checked = !$this.hasClass('checked');
        $this.toggleClass('checked', checked);
        if (checked) {
            let isChecked = true;
            parent.find('.page-multiple-td .checkbox').each(function() {
                if (!$(this).hasClass('checked')) {
                    isChecked = false;
                }
            });
            if (isChecked) {
                parent.find('.page-multiple-th .checkbox').addClass('checked');
            }
        } else {
            parent.find('.page-multiple-th .checkbox').removeClass('checked');
        }
        updateCount(parent);
    }).on('click', '.page-multiple-table .page-multiple-th .checkbox', function() {
        const $this = $(this);
        const parent = $this.closest('.page-multiple-table');
        const checked = !$this.hasClass('checked');
        parent.find('.page-multiple-th .checkbox').toggleClass('checked', checked);
        parent.find('.page-multiple-td .checkbox').toggleClass('checked', checked);
        updateCount(parent);
    });

}

/**
 * 绑定后台的导航菜单
 * @param doc 
 */
function bindNavBar(doc: JQuery<Document>) {
    doc.on('click', '.sidebar-container .sidebar-container-toggle,.app-header-container .sidebar-container-toggle,.app-wrapper .app-mask', function() {
        let box = $(this).closest('.app-wrapper').toggleClass('wrapper-min');
        if (box.find('ul').height() < $(window).height() - 100) {
            box.toggleClass('sidebar-fixed', box.hasClass('wrapper-min'));
        }
        $(window).trigger('resize');
    }).on('click', '.app-header-container .nav-item a', function(e) {
        const box = $(this).closest('.nav-item');
        if (box.find('.drop-bar').length > 0) {
            box.toggleClass('nav-drop-open');
        }
    }).on('click', '.sidebar-container li a', function() {
        let $this = $(this),
            box = $this.closest('li');
        if (box.find('ul').length > 0) {
            box.toggleClass('expand');
            return;
        }
        $('.sidebar-container li').removeClass('active');
        box.addClass('active');
        $this.closest('.app-wrapper').removeClass('wrapper-min');
    });
}
$(function() {
    const doc = $(document).on('click', ".tab-box .tab-header .fa-close", function() {
        let li = $(this).parent();
        li.closest(".tab-box").find(".tab-body .tab-item").eq(li.index()).remove();
        li.remove();
    }).on('click', '.table-toggle tr', function() {
        let $this = $(this);
        if ($this.hasClass('tr-child')) {
            return;
        }
        $this.next('.tr-child').toggle();
    }).on('click', "a[data-type=del]", function(e) {
        e.preventDefault();
        let tip = $(this).data('tip') || '确定删除这条数据？';
        if (!confirm(tip)) {
            return;
        }
        let loading = Dialog.loading();
        postJson($(this).attr('href'), function(data) {
            loading.close();
            if (data.code == 200 && !data.msg) {
                data.msg = '删除成功！';
            }
            parseAjax(data);
        });
    }).on('click', 'a[data-type=form]', function(e) {
        e.preventDefault();
        const $this = $(this);
        Dialog.box({
            url: $this.attr('href'), 
            title: $this.data('title') ?? '快速编辑', 
            ondone: function() {
                const form = this.find('form') as JQuery<HTMLFormElement>;
                ajaxForm(form.attr('action'), form.serialize(), res => {
                    if (res.code === 200) {
                        this.close();
                    }
                    parseAjax(res);
                });
            }
        });
    }).on('click', '.tree-table .tree-item-arrow', function(e) {
        e.preventDefault();
        const tr = $(this).closest('.tree-item');
        const level = toInt(tr.data('level'));
        const open = !tr.hasClass('tree-item-open');
        tr.toggleClass('tree-item-open', open);
        let next = tr.next('.tree-item');
        while (next && next.length > 0) {
            const nextLevel = toInt(next.data('level'));
            if (nextLevel <= level) {
                return;
            }
            next.toggleClass('tree-level-open', open && nextLevel === level + 1);
            next = next.next('.tree-item');
        }
    }).on('click', '.page-tooltip-bar .tooltip-toggle', function() {
        $(this).closest('.page-tooltip-bar').toggleClass('tooltip-min');
    }).on('click', '.plus-table tbody .fa-times', function() {
        const $this = $(this);
        const box = $this.closest('.plus-table');
        if (box.find('tbody tr').length === 1) {
            return;
        }
        $this.closest('tr').remove();
    }).on('click', '.plus-table tfoot .fa-plus', function() {
        const $this = $(this);
        const box = $this.closest('.plus-table');
        const tr = box.find('tbody tr').eq(0).clone(true, true);
        box.find('tbody').append(tr);
        tr.find('.form-control').val('');
    }).on('click', '.tab-header-bar .tab-item .fa-times', function(e) {
        e.preventDefault();
        $(this).closest('.tab-item').remove();
    }).on('click', 'form *[type=submit]', function(e) {
        const $this = $(this);
        const form = $this.closest('form');
        if (form.find('.tab-body').length === 0) {
            // form.trigger('submit');
            return;
        }
        let success = true;
        form.find('*[required]').each(function() {
            if (!success) {
                return;
            }
            const that = $(this);
            if (that.val()) {
                return;
            }
            that.closest('.tab-item').trigger('tab:toggle');
            Dialog.tip('表单未填写完整');
            success = false;
        });
        if (!success) {
            // form.trigger('submit');
            // e.preventDefault();
        }
    }).on('form:invalid', 'form', function(_, messages) {
        const $this = $(this);
        const key = Object.keys(messages)[0];
        let target = $this.find(`*[name=${key}]`);
        if (target.length === 0) {
            target = $this.find(`*[for=${key}]`);
        }
        if (target.length === 0) {
            return;
        }
        target.closest('.tab-item').trigger('tab:toggle');
        target.trigger('focus');
    });
    bindMultipleTable(doc);
    bindNavBar(doc);
});