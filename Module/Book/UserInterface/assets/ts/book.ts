class NovelSwiper {
    constructor(
        private target: JQuery<HTMLDivElement>
    ) {
        this.init();
    }

    private defaultMaxWidth = 800;
    private defaultMinWidth = 160;
    private defaultGap = 20;
    private itemWidth = 0;
    private $win = $(window);
    private index = -1;
    private itemCount = 0;
    private timeout = 5000;
    private timeHandler = 0;


    private init() {
        let dotBar = this.target.find('.swiper-navigation-dot');
        if (dotBar.length === 0) {
            dotBar = $('<div class="swiper-navigation-dot"></div>');
            this.target.append(dotBar);
        }
        this.itemCount = this.target.find('.swiper-item').length;
        let html = '';
        for (let i = 0; i < this.itemCount; i++) {
            html += '<span></span>';
        }
        dotBar.html(html);
        this.bindEvent();
        this.resize();
        this.scrollTo(0);
        this.bindAutoplay();
    }

    private bindAutoplay() {
        if (this.timeHandler > 0) {
            clearTimeout(this.timeHandler);
            this.timeHandler = 0;
        }
        this.timeHandler = setTimeout(() => {
            this.next();
        }, this.timeout);
    }

    public stop() {
        if (this.timeHandler > 0) {
            clearTimeout(this.timeHandler);
            this.timeHandler = 0;
        }
    }

    private bindEvent() {
        const that = this;
        this.$win.on('resize', this.resize.bind(this));
        this.target.on('click', '.swiper-navigation-dot span', function() {
            that.scrollTo($(this).index());
        }).on('mouseover', () => {
            this.stop();
        }).on('mouseleave', () => {
            this.bindAutoplay();
        });
    }

    public resize() {
        const that = this;
        this.itemWidth = Math.min(this.target.width() - this.defaultGap, this.defaultMaxWidth);
        const items = this.target.find('.swiper-item');
        const count = items.length;
        this.target.find('.swiper-item-body').width(this.itemWidth);
        this.target.find('.swiper-body').width(this.itemWidth + this.defaultMinWidth * (count - 1) + count * (this.defaultGap + 10));
        items.each(function() {
            const $this = $(this);
            $this.width($this.hasClass('swiper-item-active') ? that.itemWidth : that.defaultMinWidth);
        });
    }

    public next() {
        let i = this.index + 1;
        if (i >= this.itemCount) {
            i = 0;
        }
        this.scrollTo(i);
    }

    public previous() {
        let i = this.index - 1;
        if (i < 0) {
            i = this.itemCount - 1;
        }
        this.scrollTo(i);
    }

    public scrollTo(i: number) {
        const that = this;
        this.target.find('.swiper-item').each(function(j) {
            const isActive = i === j;
            const $this = $(this);
            $this.toggleClass('swiper-item-active', isActive);
            $this.width(isActive ? that.itemWidth : that.defaultMinWidth);
        });
        this.target.find('.swiper-navigation-dot span').eq(i).addClass('active').siblings().removeClass('active');
        const offset = (this.defaultMinWidth + this.defaultGap) * i;
        const right = offset + this.itemWidth;
        this.target.find('.swiper-body').css({
            transform: right > this.target.width() ? `translateX(${-offset}px)` : 'none'
        });
        this.index = i;
        this.bindAutoplay();
    }
}

function bindSwiper() {
    const target = $('.novel-swiper');
    if (target.length === 0) {
        return;
    }
    new NovelSwiper(target as any);
}
function bindReader() {
    if ($('.reader-container').length === 0) {
        return;
    }
    const setTheme = (name: number) => {
        settingModal.find('.theme-box .theme-' + name).addClass('active');
        $('body').addClass('theme-' + name);
        Cookies.set('theme', name as any);
    };
    const setFont = (index: number) => {
        settingModal.find('.font-box span').eq(index).addClass('active');
        $(".chapte-box").addClass('font-' + index);
        Cookies.set('font', index as any);
    };
    const setSize = (size: number) => {
        settingModal.find('.size-box .lang').text(size);
        $(".chapte-box").css('font-size', size + 'px');
        Cookies.set('size', size as any);
    };
    const toggleFull = () => {
        $(document.body).toggleClass('reader-focus-mode', $win.width() < 480 || $win.height() < 480);
    };
    const settingModal = $('.setting-dialog').on('click', '.dialog-close', () => {
        settingModal.hide();
    }).on('click', '.theme-box span', function() {
        const $this = $(this);
        let oldClass = $this.parent().find('.active').removeClass('active').attr('class');
        let newClass = $this.index();
        $('body').removeClass(oldClass);
        setTheme(newClass);
    }).on('click', '.font-box span', function() {
        const $this = $(this);
        let oldClass = $this.parent().find('.active').removeClass('active').index();
        let newClass = $this.index();
        $(".chapte-box").removeClass('font-' + oldClass);
        setFont(newClass);
    }).on('click', '.size-box .fa', function() {
        const $this = $(this);
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
    const $win = $(window).on('scroll', () => {
        $('.reader-sidebar .go-top').toggle($win.scrollTop() > 50);
    }).on('resize', toggleFull);
    $(document).on('keydown', e => {
        if (e.code === 'ArrowRight') {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = $('.control-bar .next-item a').attr('href');
            return;
        }
        if (e.code === 'ArrowLeft') {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = $('.control-bar .prev-item a').attr('href');
            return;
        }
        if (e.code === 'Enter') {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = $('.control-bar .menu-item a').attr('href');
            return;
        }
    });
    $('.reader-sidebar').on('click', '.go-top', () => {
        $win.scrollTop(0);
    }).on('click', '.do-setting', () => {
        settingModal.show();
    });
    setTheme(Cookies.get('theme') as any || 0);
    setFont(Cookies.get('font') as any || 3);
    setSize(Cookies.get('size') as any || 18);
    toggleFull();
}

$(function() {
    bindSwiper();
    bindReader();
});