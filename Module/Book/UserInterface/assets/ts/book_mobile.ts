$(function() {
    let settingBox = $("#setting-box"),
        box = $('.chapter');
    function setTheme(name: number) {
        settingBox.find('.theme-box .theme-' + name).addClass('active');
        $('body').addClass('theme-' + name);
        $.cookie('theme', name);
    }
    function setFont(index: number) {
        settingBox.find('.font-box span').eq(index).addClass('active');
        $(".chapte-box").addClass('font-' + index);
        $.cookie('font', index);
    }
    function setSize(size: number) {
        settingBox.find('.size-box .lang').text(size);
        $(".chapte-box").css('font-size', size + 'px');
        $.cookie('size', size);
    }
    function resize() {
        settingBox.css('top', ($(window).height() - settingBox.height()) / 2 + 'px');
    }
    setTheme($.cookie('theme') || 0);
    setFont($.cookie('font') || 3);
    setSize($.cookie('size') || 18);
    resize();
    $(window).resize(function() {
        resize();
    });
    $(document).on('click',function (e) {
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
    settingBox.click(function(e) {
        e.stopPropagation();
    });
    settingBox.find('.dialog-close,.dialog-yes').on('click',function() {
        box.removeClass('expanded');
    });
    settingBox.find('.theme-box span').on('click',function() {
        let oldClass = $(this).parent().find('.active').removeClass('active').attr('class');
        let newClass = $(this).index();
        $('body').removeClass(oldClass);
        setTheme(newClass);
    });
    settingBox.find('.font-box span').on('click',function() {
        let oldClass = $(this).parent().find('.active').removeClass('active').index();
        let newClass = $(this).index();
        $(".chapte-box").removeClass('font-' + oldClass);
        setFont(newClass);
    });
    settingBox.find('.size-box .fa').on('click',function() {
        let $this = $(this);
        let ele = $this.parent().find('.lang');
        let val = parseInt(ele.text()) || 18;
        let isMinus = $this.hasClass('fa-minus');
        if ((val <= 12 && isMinus) || (val >= 48 && !isMinus)) {
            return;
        }
        if (isMinus) {
            val -= 2;
        } else {
            val += 2;
        }
        setSize(val);
    });
    
});
