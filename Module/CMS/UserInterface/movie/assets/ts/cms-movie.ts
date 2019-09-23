$(function() {
    $('.screen-box').on('click', '.screen-more a', function(e) {
        e.preventDefault();
        $(this).closest('.screen-box').toggleClass('screen-open');
    }).on('click', '.line-more', function(e) {
        e.preventDefault();
        $(this).closest('.screen-line').toggleClass('line-open');
    });
});