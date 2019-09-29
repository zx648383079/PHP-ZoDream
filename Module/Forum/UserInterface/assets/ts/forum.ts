$(function() {
    $('.editor').on('keydown', 'textarea', function(e) {
        if (e.keyCode === 9) {
            e.preventDefault();
            let position = this.selectionStart + 4;
            this.value = this.value.substr(0, this.selectionStart)+'    ' + this.value.substr(this.selectionStart);
            this.selectionStart = position;
            this.selectionEnd = position;
            this.focus();
        }
    });
});