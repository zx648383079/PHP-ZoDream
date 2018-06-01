$(function() {
    let settingBox = $("#setting-box").dialog();
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
    function setWidth(width: number) {
        settingBox.find('.width-box .lang').text(width);
        $('body').addClass('width-' + width);
        $.cookie('width', width);
    }
    setTheme($.cookie('theme') || 0);
    setFont($.cookie('font') || 3);
    setSize($.cookie('size') || 18);
    setWidth($.cookie('width') || 800);
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
        settingBox.toggle();
    });
    settingBox.find('.theme-box span').click(function() {
        let oldClass = $(this).parent().find('.active').removeClass('active').attr('class');
        let newClass = $(this).index();
        $('body').removeClass(oldClass);
        setTheme(newClass);
    });
    settingBox.find('.font-box span').click(function() {
        let oldClass = $(this).parent().find('.active').removeClass('active').index();
        let newClass = $(this).index();
        $(".chapte-box").removeClass('font-' + oldClass);
        setFont(newClass);
    });
    settingBox.find('.size-box .fa').click(function() {
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
    settingBox.find('.width-box .fa').click(function() {
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