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
    $('*[data-tour]').tour({
        title: 'CMS操作引导',
        items: [
            {
                selector: '.sidebar-container',
                content: '菜单：包含所有功能操作和维护',
            },
            {
                selector: '.header-action',
                content: '点击当前站点可以快速管理所有站点',
            },
            {
                before: () => parseAjaxUri(BASE_URI + 'site'),
                selector: '.panel-container',
                content: '所有站点',
            },
            {
                selector: '.panel-container .btn-group:first',
                content: '点击“管理”进行切换站点；“编辑”可以设置站点属性：包括标题、LOGO；“配置”可以设置站点模板的属性：包括备案号、联系方式等',
            },
            {
                selector: () => $('.sidebar-container .menu-item a').filter((_, ele) => ele.innerText.indexOf('栏目') >= 0).first(),
                content: '点击“栏目”进行栏目管理，包括前台导航栏',
            },
            {
                before: () => parseAjaxUri(BASE_URI + 'category'),
                selector: '.tree-table',
                content: '所有栏目',
            },
            {
                selector: '.page-search-bar .btn-success',
                content: '添加栏目',
            },
            {
                before: () => parseAjaxUri(BASE_URI + 'category/create'),
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
            },
            {
                before: () => history.back(),
                selector: '.tree-table .btn-group:first',
                content: '针对栏目的操作：“文章”可以添加文章',
            },
            {
                before: () => $('.tree-table .btn-group a').filter((_, ele) => ele.innerText.indexOf('文章') >= 0).first().trigger('click'),
                selector: '.page-multiple-table',
                content: '当前栏目下的所有文章，不包含子栏目的文章',
            },
            {
                before: () => $('.page-search-bar .btn-group a').first().trigger('click'),
                selector: '.form-table',
                content: '编辑文章的内容',
            },
            {
                selector: '.form-table .tab-header',
                content: '切换表格内容，文章的具体内容在“高级”标签下',
            },
            {
                selector: '.form-table .btn-group',
                content: '保存操作：“确认保存”只是保存数据不显示到前台，“保存并发布”才会既保存又发布显示到前台',
            },
            {
                selector: () => $('.sidebar-container .menu-item a').filter((_, ele) => ele.innerText.indexOf('缓存管理') >= 0).first(),
                content: '前台不生效的问题都可以通过“清除缓存”解决',
            },
            {
                before: () => parseAjaxUri(BASE_URI + 'cache'),
                selector: '.form-table .btn-group',
                content: '直接点击“清除全部缓存”可以解决大部分问题',
            },
        ]
    });
    
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
    });
});