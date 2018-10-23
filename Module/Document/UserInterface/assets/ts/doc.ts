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

function delField(ele: any, url: string) {
    postJson(url, function (data) {
        if (data.code == 200) {
            $(ele).parents('tr').remove();
        }
    })
}

function getRowHtml(data: any, hasTr: boolean = true): string {
    let html: string;
    switch (data.kind + '') {
        case '3':
            html = strFormat('<td style="width: 20%">{0}</td><td style="width: 35%">{1}</td><td style="width: 35%">{2}</td><td style="width: 10%"><a href="javascript:;" onclick="editField(this, \'{3}\');" class="fa fa-pencil"></a><a href="javascript:;" onclick="delField(this, \'{4}\');" class="fa fa-trash-o"></a></td>', data.name, data.default_value, data.remark, data.edit_url, data.delete_url);
            break;
        case '2':
            html = strFormat('<td style="text-align: left;padding-left: 50px;">{1}{2}</td><td>{3}</td><td>{4}</td><td>{5}</td><td>{6}</td><td style="width: 10%"><a href="javascript:;" onclick="editField(this, \'{7}\');" class="fa fa-pencil"></a><a href="javascript:;" onclick="delField(this, \'{8}\');" class="fa fa-trash-o"></a>{9}</td>', data.parent_id < 1 ? 'warning' : '', data.parent_id > 0 ? '└' : '', data.name, data.title, data.type_label, data.mock, data.remark, data.edit_url, data.delete_url, data.has_children ? strFormat('<a href="javascript:;" onclick="addField(\'{0}\', this);" class="btn btn-xs"><i class="fa fa-fw fa-plus"></i></a>', data.child_url) : '');
            break;
        case '1':
        default:
            html = strFormat('<td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td><td style="width: 10%"><a href="javascript:;" onclick="editField(this, \'{6}\');" class="fa fa-pencil"></a><a href="javascript:;" onclick="delField(this, \'{7}\');" class="fa fa-trash-o"></a></td>', data.name, data.title, data.type_label, data.is_required ? '是' : '否', data.default_value, data.remark, data.edit_url, data.delete_url);
            break;
    }
    if (hasTr) {
        return '<tr>'+ html+'</tr>';
    }
    return html;
}


function addField(url: string, parent?: any) {
    let box = Dialog.box({
        url: url,
        title: '添加字段'
    }).on('done', function() {
        let form = this.find('form');
        postJson(form.attr('action'), form.serialize(), function(data) {
            if (data.code != 200) {
                parseAjax(data);
                return;
            }
            Dialog.remove();
            if (parent) {
                $(parent).closest('tr').after(getRowHtml(data.data))
                return;
            }
            $("#table-"+data.data.kind+" tbody:last").append(getRowHtml(data.data));
        });
    });
}

function editField(ele: any, url: string) {
    Dialog.box({
        url: url,
        title: '编辑字段'
    }).on('done', function() {
        let form = this.find('form');
        postJson(form.attr('action'), form.serialize(), function(data) {
            if (data.code != 200) {
                parseAjax(data);
                return;
            }
            Dialog.remove();
            $(ele).parents('tr').html(getRowHtml(data.data, false));
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
    $("#debug-box .js_addHeaderBtn").click(function() {
        $(this).parents('.panel').find('tbody:last').append('<tr><td style="width:40%"> <input name="header[key][]" class="form-control" placeholder="key"/> </td><td style="width:55%"> <input name="header[value][]" class="form-control" placeholder="value"/> </td><td style="width:5%"><a href="javascript:void(0);" class="fa fa-trash-o" title="删除header参数"></a> </td></tr>');
    });
    $("#debug-box .js_addRequestBtn").click(function() {
        $(this).parents('.panel').find('tbody:last').append('<tr><td style="width:40%"> <input name="request[key][]" class="form-control" placeholder="key"/> </td><td style="width:55%"> <input name="request[value][]" class="form-control" placeholder="value"/> </td><td style="width:5%"><a href="javascript:void(0);" class="fa fa-trash-o" title="删除request参数"></a> </td></tr>');
    });
    $("#debug-box .js_submit").click(function() {
        let form = $("#debug-box form");
        $.post(form.attr('action'), form.serialize(), function(html) {
            $("#debug-box .js_responseBox").html(html);
        });
    });
});