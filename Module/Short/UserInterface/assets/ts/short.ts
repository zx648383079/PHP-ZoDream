$(function() {
    $('.short-box form').submit(function() {
        let $this = $(this);
        postJson($this.attr('action'), $this.serialize(), res => {
            if (res.code !== 200) {
                parseAjax(res);
                return;
            }
            $('.short-code').show().find('code').text(res.data.short_url);
        });
        return false;
    });
    $('.short-box form input').change(function() {
        $('.short-code').hide().find('code').text('');
    });
});