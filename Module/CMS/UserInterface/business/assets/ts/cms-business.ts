function setHome(target: any) {
    const win = window as any;
    const url = window.location.href;
    let success = false;
    const items: Function[] = [
        function() {
            target.style.behavior='url(#default#homepage)';
            target.setHomePage(url);
            success = true;
        },
        function() {
            if (win.netscape) {
                win.netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                const prefs = win.Components.classes['@mozilla.org/preferences-service;1'].getService(win.Components.interfaces.nsIPrefBranch);
                prefs.setCharPref('browser.startup.homepage', url);
                success = true;
            }
        },
    ];
    for (const func of items) {
        try {
            func();
        } catch (error) {}
        if (success) {
            return;
        }
    }
    alert(`抱歉，您所使用的浏览器无法完成此操作。\n您需要手动将【${url}】设置为首页。`);
}

function addFavorite() {
    const win = window as any;
    const url = window.location.href;
    const title = document.title;
    let success = false;
    const items: Function[] = [
        function() {
            if (win.external) {
                win.external.addFavorite(url, title);
                success = true;
            }
        },
        function() {
            if (win.sidebar) {
                win.sidebar.addPanel(title, url, "");
                success = true;
            }
        },
    ];
    for (const func of items) {
        try {
            func();
        } catch (error) {}
        if (success) {
            return;
        }
    }
    alert("加入收藏失败，请使用Ctrl+D进行添加");
}

$(function() {
    $('img.lazy').lazyload({
        callback: 'img'
    });
    $('.menu-bar').on('click', '.menu-item-arrow', function() {
        $(this).closest('.menu-item').toggleClass('menu-item-open');
    });
    $(document).on('click', ".tab-box .tab-header .tab-item", function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        let tab = $this.closest(".tab-box").find(".tab-body .tab-item").eq($this.index()).addClass("active");
        tab.siblings().removeClass("active");
        tab.trigger('tabActived', $this.index());
    }).on('click', '*[modal]', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const target = $(this).attr('modal');
        $('#' + target).show();
    }).on('click', "a[data-type=ajax]", function(e) {
        e.preventDefault();
        let tip = $(this).attr('data-tip') || '确定执行此操作？';
        if (!confirm(tip)) {
            return;
        }
        $.post($(this).attr('href'), {}, res => {
            window.location.reload();
            return;
            if (res.code == 200 && res.message) {
                return;
            }
            if (res.code === 302) {
                // window.location.href = res.url;
            }
        }, 'json');
    }).on('click', function(e) {
        const target = $(e.target);
        const modal = target.hasClass('dialog') ? target : target.closest('.dialog');
        $('.dialog').each(function() {
            if (modal.is(this)) {
                return;
            }
            $(this).hide();
        });
    });
});