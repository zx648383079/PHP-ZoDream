function setTheme(name: string) {
    $('.sidebar-panel .theme-box .' + name).addClass('active');
    $('body').addClass(name);
    $.cookie('z1', name);
}
function setFont(index: number) {
    $('.sidebar-panel .font-box span').eq(index).addClass('active');
    $(".chapte-box").addClass('font-' + index);
    $.cookie('z2', index);
}
function setSize(size: number) {
    $('.sidebar-panel .size-box .lang').text(size);
    $(".chapte-box").css('font-size', size + 'px');
    $.cookie('z3', size);
}
function setWidth(width: number) {
    $('.sidebar-panel .width-box .lang').text(width);
    $('body').addClass('width-' + width);
    $.cookie('z4', width);
}
$(function() {
    setTheme($.cookie('z1') || 'theme-0');
    setFont($.cookie('z2') || 3);
    setSize($.cookie('z3') || 18);
    setWidth($.cookie('z4') || 800);
    $(document).keydown(function(event) {
        let url: string;
        if (event.keyCode == 37) {
            url = $(".chapter-control .pre a").attr('href');
        }
        if (event.keyCode == 39) {
            window.location.href = $(".chapter-control .next a").attr('href');
        }
        if (event.keyCode == 13) {
            window.location.href = $(".chapter-control .bookhome a").attr('href');
        }
        if (!url) {
            return ;
        }
        window.location.href = url;
    });
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
        $('body').removeClass(oldClass);
        setTheme(newClass);
    });
    $('.sidebar-panel .font-box span').click(function() {
        let oldClass = $(this).parent().find('.active').removeClass('active').index();
        let newClass = $(this).index();
        $(".chapte-box").removeClass('font-' + oldClass);
        setFont(newClass);
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
        setSize(val);
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
        setWidth(newVal);
    });
});