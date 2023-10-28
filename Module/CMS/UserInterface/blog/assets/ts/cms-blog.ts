declare const transLangs: any;
$(function() {
    $('img.lazy').lazyload({
        callback: 'img'
    });
    $(".template-lazy").on('lazyLoaded', function() {
        $(this).find('img.lazy').lazyload({
            callback: 'img'
        });
    }).lazyload({callback: 'tpl'});
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
    }).on('click', '.comment-item *[data-type=reply]', function(e) {
        e.preventDefault();
        const $this = $(this);
        const formBox = $('.comment-form');
        $this.closest('.comment').append(formBox);
        formBox.find('.panel-header h3').text(transLangs['reply_title']);
        formBox.find('button[type=submit]').text(transLangs['reply_btn']);
        formBox.find('input[name=parent_id]').val($this.data('id'));
    }).on('click', '.comment-form button[type=reset]', function() {
        const formBox = $(this).closest('.comment-form');
        $('.comment-floor').prepend(formBox);
        formBox.find('[name=content]').val('');
        formBox.find('.panel-header h3').text(transLangs['comment_title']);
        formBox.find('button[type=submit]').text(transLangs['comment_btn']);
        formBox.find('input[name=parent_id]').val(0);
    }).on('click', '.comment-floor .page-link-bar a', function(e) {
        e.preventDefault();
        const $this = $(this);
        const link = $this.attr('href');
        if (!link) {
            return;
        }
        const target = $this.closest('.template-lazy');
        $.get(link, function(res) {
            target.html(res).trigger('lazyLoaded');
            $('html,body').animate({
                scrollTop: target.offset().top
            }, 500);
        });
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