function setTheme(name: number) {
    $('.sidebar-panel .theme-box .theme-' + name).addClass('active');
    $('body').addClass(name);
    $.cookie('theme', name);
}
function setFont(index: number) {
    $('.sidebar-panel .font-box span').eq(index).addClass('active');
    $(".chapte-box").addClass('font-' + index);
    $.cookie('font', index);
}
function setSize(size: number) {
    $('.sidebar-panel .size-box .lang').text(size);
    $(".chapte-box").css('font-size', size + 'px');
    $.cookie('size', size);
}
function setWidth(width: number) {
    $('.sidebar-panel .width-box .lang').text(width);
    $('body').addClass('width-' + width);
    $.cookie('width', width);
}
$(function() {
    setTheme($.cookie('theme') || 0);
    setFont($.cookie('font') || 3);
    setSize($.cookie('size') || 18);
    var box = $(".chapter");
    $(".toolbar .lightoff").click(function () { 
        box.removeClass('best-eye').toggleClass('night');
    });
    $(".toolbar .huyanon").click(function () { 
        box.removeClass('night').toggleClass('best-eye');
    });
    var sizes = [
        'font-s',
        'font-l',
        'font-xl',
        'font-xxl',
        'font-xxxl'
    ];
    var pagebox = $(".page-content")
    $('.toolbar .fontsize').click(function (e) {
        if ($(this).attr('data-role') == 'inc') {
            var selected = false;
            for (var i = 0; i < sizes.length; i++) {
                const size = sizes[i];
                if (selected) {
                    pagebox.removeClass(sizes[i - 1]).addClass(size);
                    return;
                }
                if (pagebox.hasClass(size)) {
                    selected = true;
                }
            }
            return;
        }
        var selected = false;
        for (var i = sizes.length - 1; i >= 0; i--) {
            const size = sizes[i];
            if (selected) {
                pagebox.removeClass(sizes[i + 1]).addClass(size);
                return;
            }
            if (pagebox.hasClass(size)) {
                selected = true;
            }
        }
        return;
    });
    $(document).click(function (e) {
        if (!box.hasClass('min-mode')) {
            return;
        }
        var width = $(window).width(),
            height = $(window).height(),
            top = $(window).scrollTop(),
            x = e.pageX,
            y = e.pageY - top;
        if (x > width / 3 && x < width * 2 / 3 && y > height / 3 && y < height * 2 / 3) {
            box.toggleClass('expanded');
            return;
        }
        if (box.hasClass('expanded')) {
            box.removeClass('expanded');
            return;
        }
        if (x < width / 2) {
            $(window).scrollTop(top - height + 30);
            return;
        }
        $(window).scrollTop(top + height - 30);
    });
});