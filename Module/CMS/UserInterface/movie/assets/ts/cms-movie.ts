$(function() {
    $('.screen-box').on('click', '.screen-more a', function(e) {
        e.preventDefault();
        $(this).closest('.screen-box').toggleClass('screen-open');
    }).on('click', '.line-more', function(e) {
        e.preventDefault();
        $(this).closest('.screen-line').toggleClass('line-open');
    });
    $('.screen-box .screen-line').each(function() {
        let line = $(this);
        if (line.find('.line-body a:last-child').offset().top < line.offset().top + 30) {
            line.find('.line-more').remove();
        }
    });
    $('img.lazy').lazyload({
        callback: 'img'
    });
    $(document).on('click', '.tab-box .tab-header .tab-item', function() {
        let $this = $(this);
        $this.addClass('active').siblings().removeClass('active');
        $this.closest('.tab-box').find('.tab-body .tab-item').eq($this.index()).addClass('active').siblings().removeClass('active');
    }).on('click', '.nav-bar .nav-toggle', function() {
        $(this).closest('.nav-bar').toggleClass('open');
    }).on('click', '.nav-bar .search-bar .search-icon', function() {
        $(this).closest('.search-bar').toggleClass('active');
    }).on('click', '.tab-panel .panel-header .tab-header a', function(e) {
        e.preventDefault();
        let $this = $(this);
        if ($this.hasClass('active')) {
            return;
        }
        $this.addClass('active').siblings().removeClass('active');
        $this.closest('.panel-header').find('.more').attr('href', $this.attr('href'));
        $this.closest('.tab-panel').find('.panel-body .tab-item').eq($this.index()).addClass('active').siblings().removeClass('active');
    });
});