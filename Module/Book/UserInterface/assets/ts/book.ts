$(function() {
    let settingElement = $("#setting-box");
    if (settingElement.length > 0) {
        let settingBox = settingElement.dialog();
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
        $(".chapter-sidebar .do-setting").on('click',function() {
            settingBox.toggle();
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
        settingBox.find('.width-box .fa').on('click',function() {
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
    }

    
    $(".tab-switch .item").on('click',function () { 
        $(this).addClass('active').siblings().removeClass('active');
        $(this).closest('.rank-switch').find('.bd .list').eq($(this).index()).show().siblings().hide();
    });
    $(".chapter-sidebar .go-top").on('click',function() {
        $("html,body").animate({scrollTop:0}, 500);
    });
    $(window).scroll(function() {
        $(".chapter-sidebar .go-top").toggle($(this).scrollTop() > 100);
    });
    $(".n_p_box input").on('click',function(){
        $(".n_p_box input").removeClass("active");
        $(this).addClass("active");
        $(".con_box .box").toggle();
        $(".n_p_box div").toggleClass("n");
    });
    $(".tab .head a").hover(function () {
        $(this).addClass("active").siblings().removeClass("active");
        $(".tab .box").hide();
        var showBOX = $(this).attr("showBOX");
        $("." + showBOX).show();
    });
    $(".topList .tit li").hover(function() {
        $(this).addClass("Li_Mover").siblings().removeClass("Li_Mover");
        let con_list = $(this).closest('.topList').find('.con');
        con_list.removeClass("Li_Mover").eq(2- $(this).index()).addClass("Li_Mover");
    });
    $(".topList .book-list li").hover(function() {
        $(this).addClass("Li_Mover").siblings().removeClass("Li_Mover");
    });
    if ($.fn.lazyload) {
        $("img.lazy").lazyload({
            callback: 'img'
        });
    }
});