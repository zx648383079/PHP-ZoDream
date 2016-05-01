;
define(["jquery"], function () {
    $("#type").change(function () {
        var items = {
            'BIGINT': 10,
            'INT': 10,
            'TINYINT': 3,
            'SMALLINT': 5,
            'MEDIUMINT': 8,
            'DECIMAL': '10,2',
            'CHAR': 50,
            'VARCHAR': 255,
            'TEXT': ''
        };
        var name = $(this).val();
        var value = '';
        if (name) {
            value = items[name];
        }
        $('#length').val(value);
    });
    $("#formtype").change(function () {
        var type = $(this).val();
        $("#content").html('loading...');
        $.get("admin.php/content/formType?type=" + type, function (data) {
            $("#content").html(data);
        });
        $('#hidetbody').show();
        $('#select-ed').show();
        if (type == 'editor') {
            $('#hidetbody').hide();
        }
        if (type == 'merge') {
            $('#hidetbody').hide();
        }
        if (type == 'fields') {
            $('#hidetbody').hide();
            $('#select-ed').hide();
        }
        if (type == 'checkbox') {
            $('#hidetbody').hide();
        }
        if (type == 'files') {
            $('#hidetbody').hide();
        }
        if (type == 'date') {
            $('#hidetbody').hide();
        }
    });
    $("#pattern_select").change(function () {
        $('#pattern').val(this.value);
    });
    $("[name='not_null']").click(function () {
        if ($(this).val() == 1) {
            $('#pattern_data').show();
        }
        else {
            $('#pattern_data').hide();
        }
    });
});
//# sourceMappingURL=field.js.map