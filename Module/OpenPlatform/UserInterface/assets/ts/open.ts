function bindEdit() {
    $('#sign_type').change(function() {
        $("#sign_key").closest('.input-group').toggle($(this).val() > 0);
    }).trigger('change');
    $('#encrypt_type').change(function() {
        $("#public_key").closest('.input-group').toggle($(this).val() > 0);
    }).trigger('change');
    $('textarea').on('keydown', function(this: HTMLTextAreaElement, e) {
        if (e.keyCode === 9) {
            e.preventDefault();
            let position = this.selectionStart + 4;
            this.value = this.value.substr(0, this.selectionStart)+'    ' + this.value.substr(this.selectionStart);
            this.selectionStart = position;
            this.selectionEnd = position;
            this.focus();
        }
    });
}