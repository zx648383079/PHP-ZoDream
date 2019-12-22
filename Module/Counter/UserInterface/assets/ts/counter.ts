$(function() {
    $('.template-lazy').lazyload({callback: 'tpl'});
    $(document).on('ready pjax:end', function(event) {
        $(event.target).find('.template-lazy').lazyload({callback: 'tpl'});
    })
});