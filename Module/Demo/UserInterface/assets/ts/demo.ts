$(function() {
    let refresh = function() {
        let w = $(window).width();
        if (w > 650) {
            $('.metro-grid').css({
                width: w - 330,
                float: 'left'
            });
            $('iframe').css({
                display: 'inline-block'
            });
        } else {
            $('.metro-grid').css({
                width: 'auto',
                float: 'none'
            });
        }
    };
    $(window).resize(function() {
        refresh();
    });
    refresh();
    $('.metro-grid').on('click', 'a', function(e) {
        e.preventDefault();
        $('iframe').attr('src', $(this).attr('href'));
    });
    $(".template-lazy").lazyload({callback: 'tpl'});
    $(document).on('click', '.catalog-box .tree-parent>.name', function() {
        $(this).closest('.tree-parent').toggleClass('open');
    });
    $('.frame-resize a').click(function(e) {
        e.preventDefault();
        let $this = $(this);
        $this.addClass('active').siblings().removeClass('active');
        const size = $this.text();
        let width = '100%';
        let height = '100vh';
        if (size.indexOf('x') > 0) {
            const s = size.split('x');
            width = s[0].trim() + 'px';
            height = s[1].trim() + 'px';
        }
        $('#main-frame').css({
            width,
            height
        });
    });
});