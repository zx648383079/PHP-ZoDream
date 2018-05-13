function delField(ele: any, url: string) {
    postJson(url, function (data) {
        if (data.code == 200) {
            $(ele).parents('tr').remove();
        }
    })
}

function getRowHtml(data: any, hasTr: boolean = true): string {
    let html: string;
    switch (data.kind) {
        case 3:
            html = strFormat('<td style="width: 20%">{0}</td><td style="width: 35%">{1}</td><td style="width: 35%">{2}</td><td style="width: 10%"><a href="javascript:editField(this, {3});" class="fa fa-pencil"></a><a href="javascript:delField(this, {3});" class="fa fa-trash-o"></a></td>', data.name, data.default_value, data.remark, data.id);
            break;
        case 2:
            html = strFormat('<td style="width: 20%">{0}</td><td style="width: 35%">{1}</td><td style="width: 35%">{2}</td><td style="width: 10%"><a href="javascript:editField(this, {3});" class="fa fa-pencil"></a><a href="javascript:delField(this, {3});" class="fa fa-trash-o"></a></td>', data.name, data.default_value, data.remark, data.id);
            break;
        case 1:
        default:
            html = strFormat('<td style="width: 20%">{0}</td><td style="width: 35%">{1}</td><td style="width: 35%">{2}</td><td style="width: 10%"><a href="javascript:editField(this, {3});" class="fa fa-pencil"></a><a href="javascript:delField(this, {3});" class="fa fa-trash-o"></a></td>', data.name, data.default_value, data.remark, data.id);
            break;
    }
    if (hasTr) {
        return '<tr>'+ html+'</tr>';
    }
    return html;
}


function addField(url: string) {
    Dialog.box({
        url: url,
        title: '添加字段'
    }).on('done', function() {
        let form = this.find('form');
        postJson(form.attr('action'), form.serialize(), function(data) {
            if (data.code != 200) {
                parseAjax(data);
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
            //$("#table-"+data.data.kind+" tbody:last").append()
        });
    });
}

function refreshMock(url: string) {
    postJson(url, function(data) {
        if (data.code != 200) {
            parseAjax(data);
            return;
        }
        $('.json-box').html('<pre><code class="language-json">'+ Prism.highlight(JSON.stringify(data.data), Prism.languages.json) +'</code></pre>');
    });
}

$(function() {
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