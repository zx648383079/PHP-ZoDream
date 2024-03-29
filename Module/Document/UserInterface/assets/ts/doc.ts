function formatJson(json, options?: any) {
    var reg = null,
            formatted = '',
            pad = 0,
            PADDING = '    ';
    options = options || {};
    options.newlineAfterColonIfBeforeBraceOrBracket = (options.newlineAfterColonIfBeforeBraceOrBracket === true) ? true : false;
    options.spaceAfterColon = (options.spaceAfterColon === false) ? false : true;
    if (typeof json !== 'string') {
        json = JSON.stringify(json);
    } else {
        json = JSON.parse(json);
        json = JSON.stringify(json);
    }
    reg = /([\{\}])/g;
    json = json.replace(reg, '\r\n$1\r\n');
    reg = /([\[\]])/g;
    json = json.replace(reg, '\r\n$1\r\n');
    reg = /(\,)/g;
    json = json.replace(reg, '$1\r\n');
    reg = /(\r\n\r\n)/g;
    json = json.replace(reg, '\r\n');
    reg = /\r\n\,/g;
    json = json.replace(reg, ',');
    if (!options.newlineAfterColonIfBeforeBraceOrBracket) {
        reg = /\:\r\n\{/g;
        json = json.replace(reg, ':{');
        reg = /\:\r\n\[/g;
        json = json.replace(reg, ':[');
    }
    if (options.spaceAfterColon) {
        reg = /\:/g;
        json = json.replace(reg, ':');
    }
    (json.split('\r\n')).forEach(function (node, index) {
        //console.log(node);
        var i = 0,
            indent = 0,
            padding = '';

        if (node.match(/\{$/) || node.match(/\[$/)) {
            indent = 1;
        } else if (node.match(/\}/) || node.match(/\]/)) {
            if (pad !== 0) {
                pad -= 1;
            }
        } else {
                indent = 0;
        }

        for (i = 0; i < pad; i++) {
            padding += PADDING;
        }

        formatted += padding + node + '\r\n';
        pad += indent;
    });
    return formatted;
}

function dialogPost(url: string, title: string, cb: (data: any) => void) {
    Dialog.box({
        url: url,
        title: title
    }).on('done', function() {
        let that = this;
        let form = this.find('form');
        $.post(form.attr('action'), form.serialize(), function(html) {
            that.close();
            cb && cb(html);
        });
    });
}

function refreshMock(url: string) {
    postJson(url, function(data) {
        if (data.code != 200) {
            parseAjax(data);
            return;
        }
        refreshJson(data.data);
    });
}

function refreshJson(data: any) {
    $('.json-box').html('<pre><code class="language-json">'+ Prism.highlight(formatJson(data), Prism.languages.json) +'</code></pre>');
}

function bindEditProject() {
    // 新增环境
    $("body").on('click', '.js_addEnvBtn',function (event) {
        event.stopPropagation();
        const trObj = $(this).closest('tr');
        trObj.before(trObj.clone(true)).find('input').val('');
    });

    //删除环境
    $("body").on('click', '.js_deleteEnvBtn',function (event) {
        // 阻止事件冒泡
        event.stopPropagation();
        if($('.js_deleteEnvBtn').length <= 1){
            Dialog.tip('至少要保留一个环境域名')
            return false;
        }
        $(this).closest('tr').remove();
    });
    $("#type").change(function (e) { 
        $("#environment-box").toggle($(this).val() == 1);
    });
}

function bindProject(project: number) {
    $('.version-bar .btn').on('click',function() {
        const name = prompt('请输入新版本号：');
        if (!name || name.trim().length < 1) {
            return;
        }
        postJson(BASE_URI + 'project/version_new', {
            project,
            version: $('.version-bar select').val(),
            name,
        });
    });
}

function editApi(baseUri: string, apiId: number) {
    $('[name=parent_id]').change(function () { 
        $(".extent-box").toggle($(this).val() > 0);
    });
    $(document).on('click', '[data-action="add"]', function() {
        let box = $(this).closest('.panel');
        let kind = box.data('kind');
        dialogPost(baseUri + 'create_field?kind=' + kind + '&api_id=' + apiId, '添加字段', res => {
            box.find('table tbody').html(res);
        });
    }).on('click', '[data-action="import"]', function() {
        let box = $(this).closest('.panel');
        let kind = box.data('kind');
        Dialog.form({
            content: {
                type: 'textarea',
                placeholder: '允许xml、json和表单格式数据',
                style: 'width: 500px;height:300px'
            }
        }, '自动配数据').on('done', function() {
            let that = this;
            $.post(baseUri + 'import_field?kind=' + kind + '&api_id=' + apiId, that.data, function(html) {
                that.close();
                box.find('table tbody').html(html);
            });
        });
    }).on('click', '[data-action="edit"]', function() {
        let box = $(this).closest('.panel');
        let kind = box.data('kind');
        let tr = $(this).closest('tr');
        dialogPost(baseUri + 'edit_field?id=' + tr.data('id'), '编辑字段', res => {
            box.find('table tbody').html(res);
        });
    }).on('click', '[data-action="delete"]', function() {
        let box = $(this).closest('.panel');
        let kind = box.data('kind');
        let tr = $(this).closest('tr');
        $.post(baseUri + 'delete_field?id=' + tr.data('id'), html => {
            box.find('table tbody').html(html);
        });
    }).on('click', '[data-action="child"]', function() {
        let box = $(this).closest('.panel');
        let kind = box.data('kind');
        let tr = $(this).closest('tr');
        dialogPost(baseUri + 'create_field?kind=' + kind + '&api_id=' + apiId + '&parent_id='  + tr.data('id'), '添加字段', res => {
            box.find('table tbody').html(res);
        });
    });
}

$(function() {
    let clipboard = new ClipboardJS('.btn-copy');
    clipboard.on('success', function(e) {
        Dialog.tip('复制成功');
    });
    $("#debug-box #uri-list").change(function() {
        $(this).next('input').val($(this).find("option:selected").attr('data-url'));
    });
    $("#debug-box table").on('click', '.fa-trash-o', function() {
        $(this).parents('tr').remove();
    });
    $("#debug-box .js_addHeaderBtn").on('click',function() {
        $(this).parents('.panel').find('tbody:last').append('<tr><td style="width:40%"> <input name="header[key][]" class="form-control" placeholder="key"/> </td><td style="width:55%"> <input name="header[value][]" class="form-control" placeholder="value"/> </td><td style="width:5%"><a href="javascript:void(0);" class="fa fa-trash-o" title="删除header参数"></a> </td></tr>');
    });
    $("#debug-box .js_addRequestBtn").on('click',function() {
        $(this).parents('.panel').find('tbody:last').append('<tr><td style="width:40%"> <input name="request[key][]" class="form-control" placeholder="key"/> </td><td style="width:55%"> <input name="request[value][]" class="form-control" placeholder="value"/> </td><td style="width:5%"><a href="javascript:void(0);" class="fa fa-trash-o" title="删除request参数"></a> </td></tr>');
    });
    $("#debug-box .js_submit").on('click',function() {
        let form = $("#debug-box form");
        $.post(form.attr('action'), form.serialize(), function(html) {
            $("#debug-box .js_responseBox").html(html);
        });
    });
    $('.version-bar select').change(function() {
        const url = window.location.href;
        window.location.href = url + (url.indexOf('?') > 0 ? '&' : '?') + 'version=' + $(this).val();
    });
    if ($('#coder-dialog').length > 0) {
        let coder = $('#coder-dialog').dialog();
        coder.find('.dialog-header form').submit(function() {
            let $this = $(this);
            let val = $this.find('[name=lang]').val();
            postJson($this.attr('action'), $this.serialize(), function(data) {
                if (data.code != 200) {
                    parseAjax(data);
                    return;
                }
                coder.find('.dialog-body').html('<pre><code class="language-'+val+'">'+ Prism.highlight(data.data, Prism.languages[val]) +'</code></pre>');
                coder.showCenter();
            });
            return false;
        });
        $('a[data-action="code"]').on('click',function() {
            coder.show();
        });
    }
});