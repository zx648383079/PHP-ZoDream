$(function() {
    $(".tab-switch .item").click(function () { 
        $(this).addClass('active').siblings().removeClass('active');
        $('.rank-switch .bd .list').eq($(this).index()).show().siblings().hide();
    });
    $(".chapter-sidebar .go-top").click(function() {
        $("html,body").animate({scrollTop:0}, 500);
    });
    $(window).scroll(function() {
        $(".chapter-sidebar .go-top").toggle($(this).scrollTop() > 100);
    });
    $(".chapter-sidebar .do-setting").click(function() {
        $(this).find('.sidebar-panel').toggle();
    });
    $('.sidebar-panel').click(function(e) {
        e.stopPropagation();
    });
    $('.sidebar-panel .fa-close').click(function() {
        $(this).parents('.sidebar-panel').hide();
    });
    $('.sidebar-panel .theme-box span').click(function() {
        let oldClass = $(this).parent().find('.active').removeClass('active').attr('class');
        let newClass = $(this).attr('class');
        $(this).addClass('active');
        $('body').removeClass(oldClass).addClass(newClass);
    });
    $('.sidebar-panel .font-box span').click(function() {
        $(this).addClass('active');
    });
    $('.sidebar-panel .size-box .fa').click(function() {
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
        ele.text(val);
        $(".chapte-box").css('font-size', val + 'px');
    });
    $('.sidebar-panel .width-box .fa').click(function() {
        let list = [
            640,
            800,
            900,
            1280
        ];
        let $this = $(this);
        let ele = $this.parent().find('.lang');
        let val = parseInt(ele.text()) || 800;
        let isMinus = $this.hasClass('fa-minus');
        let index = list.indexOf(val);
        if ((index <= 0 && isMinus) || (index >= list.length - 1 && !isMinus)) {
            return;
        }
        let newVal = isMinus ? list[index - 1] : list[index + 1];
        ele.text(newVal);
        $('body').removeClass('width-' + val).addClass('width-' + newVal);
    });
});