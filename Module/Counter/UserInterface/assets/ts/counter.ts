function bindLoad(box: JQuery<any>) {
    box.find('.template-lazy').lazyload({callback: 'tpl'});
    box.find('[name=start_at]').datetimer();
    box.find('[name=end_at]').datetimer();
}

$(function() {
    bindLoad($(document));
    $(document).on('ready pjax:end', function(event) {
        bindLoad($(event.target));
    });
});

function bindHome() {
    $('.tab-header a').on('click',function(e) {
        e.preventDefault();
        let $this = $(this);
        let type = $this.data('type');
        $this.addClass('active').siblings().removeClass('active');
        $('.row .template-lazy').each(function() {
            let that = $(this);
            let url = that.data('url');
            if (that.data('old')) {
                url = that.data('old');
            } else {
                that.data('old', url);
            }
            that.attr('data-url', url + '?start_at=' + type);
        }).trigger('lazy-refresh');
        $(window).trigger('scroll');
    });
}