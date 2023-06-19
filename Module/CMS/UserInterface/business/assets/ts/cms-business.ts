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

/**
 * 转化请求响应结果
 * @param data 
 */
function parseAjax(data: IResponse) {
    if (data.code === 302 || (data.code === 401 && data.url)) {
        window.location.href = data.url;
        return;
    }
    if (data.code !== 200) {
        Dialog.tip(data.message || '操作执行失败！');
        return;
    }
    Dialog.tip(data.message || '操作执行完成！');
    if (data.data && data.data.refresh) {
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }
    if (data.data && data.data.url) {
        setTimeout(() => {
            if (data.data.url === -1) {
                history.go(-1);
                return;
            }
            window.location.href = data.data.url;
        }, 500);
    }
};

function updateModalCenter(modal: JQuery) {
    modal.css({
        left: Math.max(window.innerWidth - modal.width(), 0) / 2,
        top: Math.max(window.innerHeight - modal.height(), 0) / 2,
        margin: 0,
    });
}

$(function() {
    $('img.lazy').lazyload({
        callback: 'img'
    });
    $('.menu-bar').on('click', '.menu-item-arrow', function() {
        $(this).closest('.menu-item').toggleClass('menu-item-open');
    });
    $(window).on('resize', function() {
        $('.dialog:visible').each(function() {
            updateModalCenter($(this));
        });
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
        const modal = $('#' + target).show();
        updateModalCenter(modal);
    }).on('submit', "form[data-type=ajax]", function() {
        let $this = $(this);
        let loading = Dialog.loading();
        postJson($this.attr('action'), $this.serialize(), function(data) {
            loading.close();
            parseAjax(data);
        });
        return false;
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
    }).on('click', '.code-input-group img', function(e) {
        e.preventDefault();
        const img = $(this);
        const url = img.attr('src') as string;
        img.attr('src', url.split('?')[0] + '?v=' + Math.random());
    }).on('click', '.dialog .dialog-close', function() {
        $(this).closest('.dialog').hide();
    }).on('click', '.dialog .dialog-submit', function() {
        $(this).closest('.dialog').find('form').trigger('submit');
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