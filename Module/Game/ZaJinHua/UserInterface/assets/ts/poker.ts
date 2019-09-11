function parseAjaxUri(uri: string, params?: {[key: string]: any}) {
    if (params) {
        let data = [];
        $.each(params, (i, item) => {
            data.push(i + '=' + item);
        });
        uri += (uri.indexOf('?') >= 0 ? '&' : '?') + data.join('&');
    }
    $.pjax({url: uri, container: '#game-box'});
}
$(function() {
    $(document).pjax('a', '#game-box');
    let box = $('#game-box').on('keydown', '.input-box input', function(e) {
        if (e.originalEvent.code !== 'Enter') {
            return;
        }
        let $this = $(this);
        let val = $this.val();
        let min = $this.attr('min');
        let max = $this.attr('max');
        if (min && val < min) {
            Dialog.tip('最小为' + min);
            return;
        }
        if (max && val > max) {
            Dialog.tip('最大为' + max);
            return;
        }
        parseAjaxUri($this.closest('.input-box').data('url'), {
            [$this.attr('name')]: val  
        });
    });
});