$(function() {
    $('img.lazy').lazyload({
        callback: 'img'
    });
    $(".template-lazy").lazyload({callback: 'tpl'});
    $(document).on('click', '.nav-bar li', function(e) {
        const $this = $(this);
        if ($this.find('.nav-sub-bar').length === 0) {
            return;
        }
        e.preventDefault();
        $this.toggleClass('nav-open').siblings().removeClass('nav-open');
    }).on('mouseenter', '.nav-bar li', function(e) {
        const $this = $(this);
        $this.siblings().removeClass('nav-open');
        if ($this.find('.nav-sub-bar').length === 0) {
            return;
        }
        e.preventDefault();
        $this.addClass('nav-open');
    }).on('click', '.nav-toggle-arrow', function() {
        $(document.body).toggleClass('nav-fully-open');
    }).on('click', '.nav-dialog-mask', function() {
        $(document.body).removeClass('nav-fully-open');
    }).on('click', '.search-toggle-arrow', function(e) {
        e.preventDefault();
        $('.search-bar').toggleClass('search-open');
    }).on('click', '.drop-toggle-arrow', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).closest('.drop-container').toggleClass('drop-open');
    }).on('click', function(e) {
        const target = $(e.target);
        if (target.closest('.nav-bar').length === 0) {
            $('.nav-bar li').removeClass('nav-open');
        }
        if (target.closest('.drop-container').length === 0) {
            $('.drop-container').removeClass('drop-open');
        }
    });
});