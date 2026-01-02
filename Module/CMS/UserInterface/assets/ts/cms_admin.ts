declare const UE: any;
function bindField(baseUri: string) {
    let field = $('input[name=field]');
    $('input[name=name]').on('blur', function() {
        pinyinIfEmpty(field, $(this).val() as string);
    });
    $('input[name=type]').on('change', function() {
        $.get(baseUri, {
            id: $('[name=id]').val(),
            type: $(this).val()
        }, function(html) {
            $('.option-box').html(html);
        });
    }).trigger('change');
    $('.add-box button').on('click',function() {
        let input = $(this).prev(),
            val = input.val().toString().trim();
        if (val.length < 1) {
            return;
        }
        input.val('');
        let box = $(this).closest('.tab-group');
        let num = box.find('.radio-label').length;
        box.find('[name=tab_name]').removeAttr('checked');
        let addBox = $(this).closest('.add-box');
        addBox.before(`<span class='radio-label'>
        <input type='radio' id='tab_name${num}' name='tab_name' value='${val}' checked>
        <label for='tab_name${num}'>${val}</label>
    </span>`);
        if (num >= 4) {
            addBox.remove();
        }
    });
}

function bindSite() {
    $(document).on('change', 'select[name=theme]', function(e) {
        const siteId = $(this).closest('form').find('[name=id]').val() as any;
        if (siteId && siteId > 0) {
            confirm('你正在更改主题，更改主题将删除站点数据！');
        }
    });
}

function bindCat() {
    $('input[name=title]').on('blur', function() {
        pinyinIfEmpty($('input[name=name]'), $(this).val() as any);
    });
    $('input[name=type]').on('click', function() {
        const val = toInt($(this).val());
        $('input[name=url]').closest('.input-group').toggle(val > 1);
        $('.editor-box').data('_instance')?.toggle(val === 1);
    }).each(function(this: HTMLInputElement) {
        if (this.checked) {
            $(this).trigger('click');
        }
    });
    const urlDialog = $('.url-dialog').dialog();
    const urlQuries: string[] = [];
    let urlTarget: JQuery;
    urlDialog.on('done', function() {
        const ele = this.find('.option-list-item.selected');
        const value = ele.data('value');
        if (!value) {
            return;
        }
        urlTarget.val(value);
        this.close();
    });
    const tapQueryUrl = (data: any) => {
        postJson(BASE_URI + 'home/query_url', {
            step: urlQuries,
            ...data
        }, res => {
            if (res.code !== 200) {
                parseAjax(res);
                return;
            }
            let html = '';
            $.each(res.data, function() {
                let key = this.next ? 'next' : 'value';
                html += `<div class="option-list-item" data-${key}="${this[key]}">${this.name}</div>`;
            });
            urlDialog.find('.flip-tab-item.active .list-scroll-body').html(html);
            urlDialog.showCenter();
        });
    };
    urlDialog.box.on('click', '.column-item', function() {
        const $this = $(this);
        urlQuries.splice(0);
        urlQuries.push($this.data('next'));
        const tab = $this.closest('.flip-tab-item');
        tab.removeClass('active');
        tab.next('.flip-tab-item').addClass('active');
        urlDialog.showCenter();
        tapQueryUrl({});
    }).on('click', '.option-list-item', function() {
        const $this = $(this);
        $this.addClass('selected').siblings().removeClass('selected');
        const next = $this.data('next');
        if (next) {
            urlQuries.push(next);
            tapQueryUrl({});
        }
    }).on('submit', 'form', function(e) {
        e.preventDefault();
        const data: any = {};
        for (const item of $(this).serializeArray()) {
            data[item.name] = item.value;
        }
        tapQueryUrl(data);
        return false;
    });
    $(document).on('click', '*[data-help]', function(e) {
        e.preventDefault();
        urlDialog.showCenter();
        urlDialog.find('.flip-tab-item').eq(0).addClass('active').siblings().removeClass('active');
        urlTarget = $(this).prev();
    });
}

function pinyinIfEmpty(ele: JQuery, val: string) {
    if (ele.val() || !val) {
        return;
    }
    postJson(BASE_URI + 'home/generate', {
        name: val
    }, function(data) {
        if (data.code != 200) {
            return;
        }
        ele.val(data.data.replace(/\s/g, '').toLowerCase()).trigger('blur');
    });
}

function bindEditModel() {
    let valIfEmpty = function(ele: JQuery, val: string) {
        if (!ele.val()) {
            ele.val(val);
        }
    },
    table = $('input[name=table]').on('blur', function() {
        let val = $(this).val();
        if (!val) {
            return;
        }
        valIfEmpty($('input[name=category_template]'), val + '');
        valIfEmpty($('input[name=list_template]'), val + '_list');
        valIfEmpty($('input[name=show_template]'), val + '_detail');
    });
    $('input[name=name]').on('blur', function() {
        pinyinIfEmpty(table, $(this).val() as any);
    });
    $('input[name=type]').on('click', function() {
        const val = toInt($(this).val())
        $('.content-box').toggle(val < 1);
        $('.form-box').toggle(val > 0);
    }).each(function(this: HTMLInputElement) {
        if (this.checked) {
            $(this).trigger('click');
        }
    });
}

function bindEditOption() {
    $('.option-box .input-group .fa-times').on('click', function() {
        $(this).closest('.input-group').remove();
    });
}
$(function() {
    setTimeout(() => {
        $('.column-full-item .overlay').remove();
    }, 1000);
    $(document).on('click', '.multi-input-container .selected-container .item-close', function() {
        $(this).closest('.selected-item').remove();
    }).on('click', '.multi-input-container .add-container .add-item', function() {
        const $this = $(this);
        const target = $this.closest('.multi-input-container').find('.selected-container');
        const input = $this.closest('.add-container').find('select:last,input,textarea');
        const val = input.val();
        let label = val;
        if (input[0].nodeName === 'SELECT') {
            const ele = input[0] as HTMLSelectElement;
            label = ele.options[ele.selectedIndex].text;
        }
        const name = input.attr('name').substring(4);
        target.append(`<div class='selected-item'>
        <span class='item-close'>&times;</span>
        <div class='item-label'>${label}</div>
        <input type='hidden' name='${name}[]' value='${val}'>
    </div>`);
    }).on('click', 'button[data-type=publish]', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        form.append('<input type="hidden" name="status" value="5">');
        form.trigger('submit');
    }).on('click', '*[data-tour]', function(e) {
        e.preventDefault();
        $(this).tour({
            title: 'CMS操作引导',
            items: [
                {
                    selector: '.sidebar-container',
                    content: '菜单',
                },
                {
                    selector: '.form-table',
                    content: '表格',
                },
                {
                    selector: '.form-table .tab-header',
                    content: '切换表格内容',
                },
                {
                    selector: '.form-table .btn-group',
                    content: '保存操作',
                }
            ]
        }).open();
    });
});