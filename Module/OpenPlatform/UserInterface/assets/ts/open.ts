function bindEdit() {
    $('#sign_type').change(function() {
        $("#sign_key").closest('.input-group').toggle($(this).val() > 0);
    }).trigger('change');
    $('#encrypt_type').change(function() {
        $("#public_key").closest('.input-group').toggle($(this).val() > 0);
    }).trigger('change');
}