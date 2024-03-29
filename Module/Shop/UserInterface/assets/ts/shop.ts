const CHECKED_CHANGE = 'cart_checked_change';
function addToCart(id: number, amount: number|boolean = 1, properties?: string[]) {
    if (typeof amount === 'boolean') {
        amount = parseInt($('.number-input').val() + '', 10);
    }
    if (amount < 1) {
        Dialog.tip('数量不能小于1');
        return;
    }
    postJson(BASE_URI + 'cart/add', {
        goods: id,
        amount: amount,
        properties: JSON.stringify(properties)
    }, function(data) {
        parseAjax(data);
        if (data.code === 200) {
            $('.header-cart').trigger('cartRefresh');
        }
    });
}

function buyGoods(id: number, amount: number|boolean = 1, properties?: string[]) {
    if (typeof amount === 'boolean') {
        amount = parseInt($('.number-input').val() + '', 10);
    }
    if (amount < 1) {
        Dialog.tip('数量不能小于1');
        return;
    }
    window.location.href = BASE_URI + 'cashier?type=1&cart='+ JSON.stringify([
        {
            goods: id,
            amount: amount,
            properties: properties
        }
    ]);
}

function collectGoods(id: number, target?: any) {
    postJson(BASE_URI + 'collect/toggle?id=' + id, function(data) {
        if (data.code != 200) {
            return;
        }
        target && $(target).toggleClass('active', data.data);
    });
}

function mapItem(cb: (JQuery) => any) {
    $(".cart-item").each(function () {
        if (cb($(this)) == false) {
            return false;
        }
    });
}

function mapCheckedItem(cb: (JQuery) => any) {
    mapItem(function (goods: JQuery) {
        if (!goods.find('.check-box').hasClass('active')) {
            return;
        }
        if (cb(goods) == false) {
            return false;
        }
    });
}

function refreshCart() {
    let count = 0,
        total = 0;
    mapCheckedItem(item => {
        count ++;
    });
    $(".cart-footer .cart-checked-count").text(count);
    
}
function search(keywords: string) {
    window.location.href = $(".header-search").data('url') + '?keywords='+ keywords;
}
$(function() {
    $('.back-to-top').on('click',function() {
        $('body,html').animate({
            scrollTop: 0
        }, 100);
    });
    $(".check-box").on('click',function() {
        $(this).toggleClass('active').trigger(CHECKED_CHANGE);
    });
    $(".toggle-box").on('click',function() {
        $(this).toggleClass('active').trigger('change');
    });
    $('.number-box .fa-minus').on('click',function() {
        let input = $(this).closest('.number-box').find('input'),
            min = input.attr('min') || 0,
            amount = Number(input.val()) || 0;
        if (amount > min) {
            amount -= 1;
        }
        input.val(amount).trigger('change');
    });
    $('.number-box .fa-plus').on('click',function() {
        let input = $(this).closest('.number-box').find('input'),
            max = input.attr('max') || 999,
            amount = Number(input.val()) || 0;
        if (amount < max) {
            amount += 1;
        }
        input.val(amount).trigger('change');
    });
    $(".cart-footer .btn").on('click',function(e) {
        e.preventDefault();
        let ids = [];
        mapCheckedItem(function(item) {
            ids.push(item.data('id'));
        });
        if (ids.length < 1) {
            Dialog.tip('请选择结算的商品');
            return;
        }
        window.location.href = $(this).attr('href') + '?cart=' + ids.join('-');
    });
    $('.checkout-footer').on('click', '.btn', function(e) {
        e.preventDefault();
        let form = $(this).closest('form'),
            is_check = true;
        $.each(form.serializeArray(), function() {
            if (this.name == 'address' && parseInt(this.value) < 1) {
                Dialog.tip('请选择收货地址');
                is_check = false;
                return false;
            }
            if (this.name == 'shipping' && parseInt(this.value) < 1) {
                Dialog.tip('请选择配送方式');
                is_check = false;
                return false;
            }
            if (this.name == 'payment' && parseInt(this.value) < 1) {
                Dialog.tip('请选择支付方式');
                is_check = false;
                return false;
            }
        });
        if (!is_check) {
            return;
        }
        ajaxForm(form.attr('action'), form.serialize());
    });
    $(".header-nav>li").mouseenter(function() {
        $(".header-nav .nav-dropdown").hide();
        $(this).find('.nav-dropdown').show();
    });
    $(".scroll-nav .nav-arrow").on('click',function() {
        $(this).closest('.scroll-nav').toggleClass('unfold');
    });
    $(".header-nav").mouseleave(function() {
        $(this).find('.nav-dropdown').hide();
    });
    let miniCart = $(".header-cart").mouseover(function() {
        $(this).find('.cart-dialog').show();
    }).mouseout(function() {
        $(this).find('.cart-dialog').hide();
    }).on('cartRefresh', function() {
        let $this = $(this);
        $.get($this.data('url'), html => {
            $this.html(html);
        });
    }).on('click', '.action .fa-times', function() {
        $.getJSON(BASE_URI + 'cart/delete', {
            id: $(this).closest('.cart-item').data('id'),
        }, function(data) {
            if (data.code == 200) {
                miniCart.trigger('cartRefresh');
            }
        });
    });
    $(".header-search").mouseover(function() {
        $(this).addClass('expanded');
    }).mouseout(function() {
        $(this).removeClass('expanded');
    });
    $('img.lazy').lazyload({callback: 'img'});
    $(".template-lazy").lazyload({callback: 'tpl'});
    $(".more-load").lazyload({
        mode: 1,
        callback: function(moreEle: JQuery) {
            if (moreEle.data('is_loading')) {
                return;
            }
            moreEle.data('is_loading', true);
            let page: number = parseInt(moreEle.data('page') || '0') + 1;
            let url = moreEle.data('url');
            let target = moreEle.data('target');
            $.getJSON(url, {
                page: page
            }, function (data) {
                moreEle.data('is_loading', false)
                if (data.code != 200) {
                    return;
                }
                $(target).append(data.data.html);
                moreEle.data('page', page);
                if (!data.data.has_more) {
                    moreEle.remove();
                }
            });
        }
    });
    let searchInput = $(".header-search input").keydown(function(e) {
        if (e.keyCode == 13) {
            search($(this).val() as string);
        }
    });
    $(".header-search .fa-search").on('click',function() {
        search(searchInput.val() as string);
    });
    $(window).scroll(function() {
        $(".header-main").toggleClass('top-fixed', $(this).scrollTop() > 180);
    }).trigger('scroll');
});

function bindCart() {
    $('.number-box input').change(function() {
        let _this = $(this),
            amount = _this.val(),
            box = _this.closest('.cart-item'),
            id = box.attr('data-id');
        $.getJSON(BASE_URI + 'cart/update', {
            id: id,
            amount: amount
        }, function(data) {
            parseAjax(data);
        });
    });
    $('.cart-item .fa-trash').on('click',function() {
        let _this = $(this),
            box = _this.closest('.cart-item'),
            id = box.attr('data-id');
        $.getJSON(BASE_URI + 'cart/delete', {
            id: id,
        }, function(data) {
            parseAjax(data);
            if (data.code == 200) {
                box.remove();
            }
        });
    });
    $(".cart-group-item .group-header .check-box").on(CHECKED_CHANGE, function() {
        let $this = $(this),
            checked = $this.hasClass('active');
        togglecChecked(checked, $this.closest('.cart-group-item').find('.cart-item .check-box'));
        if (!checked) {
            togglecChecked(checked, checkAll);
        }
        refreshCart();
    });
    $(".cart-item .check-box").on(CHECKED_CHANGE, function() {
        let $this = $(this);
        if (!$this.hasClass('active')) {
            togglecChecked(false, checkAll, $this.closest('.cart-group-item').find('.group-header .check-box'));
        }
        refreshCart();
    });
    let checkAll = $(".cart-footer .check-box, .cart-header .check-box").on(CHECKED_CHANGE, function() {
        togglecChecked($(this).hasClass('active'), $(".cart-group-item .check-box"), checkAll)
        refreshCart();
    })
}

function togglecChecked(checked: boolean, ...args: JQuery[]) {
    args.forEach(items => {
        items.each(function(this: HTMLInputElement) {
            let item = $(this);
            item.toggleClass('active', checked);
            if (!item.is('input')) {
                return;
            }
            this.checked = checked;
        });
    });
}

function goPhoneLogin() {
    $(".login-type-box").hide();
    $(".login-box").removeClass('hide');
}

function goEmailLogin() {
    $(".login-type-box").hide();
    $(".login-box").removeClass('hide');
    $(".login-box .email-password").removeClass('hide');
    $(".login-box .phone-code,.login-box .phone-password").addClass('hide');
}

function bindStore() {
    let $window = $(window),
        page = $('.store-page'),
        beforeScrollTop = $window.scrollTop();
    $window.scroll(function() {
        let scrollTop = $window.scrollTop(),
            isUp = scrollTop - beforeScrollTop < 0;
        beforeScrollTop = scrollTop;
        if (scrollTop < 183) {
            page.removeClass('min').removeClass('min-up');
            return;
        }
        if (isUp) {
            page.addClass('min-up').removeClass('min');
            return;
        }
        page.addClass('min').removeClass('min-up')
    });
}

function bindCategory() {
    $(".category-menu .menu-item").on('click',function() {
        let $this = $(this);
        $this.addClass('active').siblings().removeClass('active');
        let box = $(".category-main .item").eq($this.index());
        box.addClass('active').siblings().removeClass('active');
        if (box.hasClass('lazy-loading')) {
            box.removeClass('lazy-loading');
            $.get(box.data('url'), function(html) {
                box.html(html);
            });
        }
    }).eq(0).trigger('click');
}

function bindCartDialog(goodsId: number) {
    let cartSelectedProperty = $('.selected-property'),
        mapSelectedProperties = function(cb: (JQuery) => void) {
            cartDialog.find('.group .group-body .active').each(function() {
                cb && cb($(this));
            });
        },
        refreshProduct = function() {
            let amount = parseInt(cartDialog.find('.number-box .number-input').val()+''), properties = getSelectedProperties();
            postJson(BASE_URI + 'goods/price', {
                id: goodsId,
                amount: amount,
                properties: JSON.stringify(properties)
            }, function(data) {
                if (data.code != 200) {
                    parseAjax(data);
                    return;
                }
                cartDialog.find('.price').html(data.data.price);
                cartDialog.find('.stock').html('库存：' + data.data.stock);
            });
        },
        getSelectedProperties = function() {
            let data = [];
            mapSelectedProperties(function(item: JQuery) {
                data.push(item.data('value'));
            });
            return data;
        },
        refreshSelected = function() {
            let text = [];
            mapSelectedProperties(function(item: JQuery) {
                text.push(item.text());
            });
            cartSelectedProperty.text(text.length > 0 ? '已选：' +text.join(' '): '');
        },
        cartDialog = $('.cart-dialog').on('click', '.dailog-yes', function(e) {
        e.preventDefault();
        let amount = parseInt(cartDialog.find('.number-box .number-input').val()+''), properties = getSelectedProperties();
        if (cartDialog.data('step') == 'buy') {
            buyGoods(goodsId, amount, properties);
        } else {
            addToCart(goodsId, amount, properties);
        }
        cartDialog.hide();
    }).on('click', '.dialog-close', function(e) {
        e.preventDefault();
        cartDialog.hide();
    }).on('click', '.group .group-body span', function() {
        let $this = $(this);
        if ($this.hasClass('disable')) {
            return;
        }
        if ($this.closest('.multi-group').length > 0) {
            $this.toggleClass('active');
        } else{
            $this.addClass('active').siblings().removeClass('active');
        }
        refreshSelected();
        refreshProduct();
    }).on('change', '.number-box .number-input', function() {
        refreshProduct();
    }).on('click',function(e) {
        if (e.pageY < cartDialog.find('.dialog-body').offset().top) {
            cartDialog.hide();
        }
    });
    $(document).on('click', '[data-action=cart]', function(e) {
        e.preventDefault();
        cartDialog.show().data('step', 'cart');
    }).on('click', '[data-action=buy]', function(e) {
        e.preventDefault();
        cartDialog.show().data('step', 'buy');
    });
    $(window).scroll(function() {
        let top = $(this).scrollTop();
        $('.top-tab a').removeClass('active').each(function() {
            let item = $(this), 
                target = $(item.attr('href')),
                y = target.offset().top;
            if (y <= top && top < y + target.height()) {
                item.addClass('active');
                return false;
            }
        });
    });
}

function bindMobileCashier() {
    let form = $('form');
    $('select').change(function() {
        ajaxForm(form.attr('action').replace('checkout', 'preview'), form.serialize(), function(data) {
            if (data.code != 200) {
                return parseAjax(data);
            }
            $.each(data.data, function(key: string, item) {
                if (typeof item != 'object') {
                    form.find('*[data-key=' + key + ']').html(item);
                }
            });
        });
    }).select();
}

function bindComment() {
    $('.comment-star i').on('click',function() {
        let $this = $(this),
            star = $this.index() + 1,
            box = $this.closest('.comment-star');
        box.find('input').val(star);
        box.find('i').each(function() {
            $(this).toggleClass('active', $(this).index() < star);
        });
    });
    $('.comment-form-item').submit(function() {
        let form = $(this);
        ajaxForm(form.attr('action'), form.serialize(), function(data) {
            if (data.code != 200) {
                return parseAjax(data);
            }
            if ($('.comment-form-item').length > 1) {
                form.remove();
                Dialog.tip('已提交评价');
                return;
            }
            return parseAjax(data);
        });
        return false;
    });
    $('.multi-image-box .add-item').upload({
        url: '/ueditor.php?action=uploadimage',
        name: 'upfile',
        multiple: true,
        isAppend: false,
        allowMultiple: true,
        removeTag: '.fa-times',
        template: '<div class="image-item"><img src="{url}" alt=""><input type="hidden" name="images[]" value="{url}"><i class="fa fa-times"></i></div>',
        onafter: function(data) {
            return data.state == 'SUCCESS' ? data : false;
        }
    });
}

function bindMemberCenter() {
    $(window).on('scroll', function() {
        $('header.top').toggleClass('fixed', $(window).scrollTop() < 176);
    });
}

function bindPay() {
    let paymentInput = $('input[name=payment]'),
        payments = $('.payment-item').on('click',function() {
        let $this = $(this);
        payments.removeClass('active');
        $this.addClass('active');
        paymentInput.val($this.data('id'));
    });
}

function bindAddress() {
    let dialog = $('.address-dialog').dialog();
    let fillForm = function(data?: any) {
        dialog.find('.dialog-title').text(data ? '修改地址' : '新建地址');
        dialog.find('input,textarea').each(function(this: HTMLInputElement) {
            if (this.type === 'checkbox') {
                this.checked = !data || data[this.name];
                return;
            }
            this.value = data && data[this.name] ? data[this.name] : '';
        });
        if (data && data.region_id > 0) {
            regionBox.val = data.region_id;
        }
    };
    let regionBox = $('.address-dialog .region-input').multiSelect({
        default: 0,
        data: BASE_URI + 'region/tree',
        tag: 'region_id'
    });
    dialog.on('done', function() {
        this.close();
        postJson(BASE_URI + 'address/save', formData(dialog), parseResponse);
    });
    $('.add-btn').on('click',function() {
        fillForm();
        dialog.show();
    });
    let pageBox = $('.address-page-box').on('click', '.pagination a', function(e) {
        e.preventDefault();
        replaceUrl($(this).attr('href'));
    }).on('click', 'a[data-action="edit"]', function(e) {
        e.preventDefault();
        $.getJSON($(this).attr('href'), res => {
            fillForm(res.data);
            dialog.show();
        });
    }).on('click', 'a[data-action="del"]', function(e) {
        e.preventDefault();
        if (!confirm('确定要删除此收货地址?')) {
            return;
        }
        postJson($(this).attr('href'), parseResponse);
    }).on('click', 'a[data-action="default"]', function(e) {
        e.preventDefault();
        postJson($(this).attr('href'), parseResponse);
    }),
    parseResponse = function(data: any) {
        if (data.code !== 200) {
            parseAjax(data);
            return;
        }
        if (typeof data.data !== 'object') {
            return;
        }
        let url = data.data.url;
        if (data.data.refresh) {
            url = window.location.href;
        }
        replaceUrl(url);
    },
    replaceUrl = function(url: string) {
        partialLoad(pageBox, url, '我的收货地址');
    };
}

function bindPayTime() {
    let items = $('[data-type="countdown"]');
    setInterval(function() {
        const now = Math.floor(new Date().getTime() / 1000);
        items.each(function() {
            let ele = $(this);
            const diff = Math.max(ele.data('end') - now, 0);
            ele.text(formatTime(diff));
        });
    }, 1000);
}

function twoPad(i: number): string {
    return i < 10 && i >= 0 ? '0' + i : i.toString();
}

function formatTime(diff: number) {
    let h = Math.floor(diff / 3600);
    diff -= h * 3600;
    let m = Math.floor(diff / 60);
    let s = diff % 60;
    if (h !== 0) {
        return twoPad(h) + '时' + twoPad(m) + '分' + twoPad(s) + '秒';
    }
    if (m !== 0) {
        return twoPad(m) + '分' + twoPad(s) + '秒';
    }
    return twoPad(s) + '秒';
}

function bindHome() {
    var silder = $('.banner .slider').slider({
        width: 1,
        height: 420,
    });
    $('.template-lazy').on('lazyLoaded', function() {
        let box = $(this).find('.slider-goods');
        if (box.length < 1) {
            return;
        }
        box.slider({
            width: 266,
            height: 344,
            haspoint: false
        });
    });
}

function bindGoods(goods: number) {
    $('.template-lazy').on('lazyLoaded', function() {
        let box = $(this).find('.slider-goods');
        if (box.length < 1) {
            return;
        }
        box.slider({
            width: 266,
            height: 344,
            haspoint: false
        });
    });
    $('.picture-box').on('mouseover', 'ul li', function() {
        let $this = $(this);
        $this.addClass('active').siblings().removeClass('active');
        $this.closest('.picture-box').find('.view img').attr('src', $this.find('img').attr('src'));
    });
    $('.detail-box .tab-body .tab-item').on('tab:actived', function(_, i) {
        if (i > 0) {
            lazyTab($(this));
        }
    });
    $('#comment-tab').on('click', '.pagination a', function(e) {
        e.preventDefault();
        let $this = $(this);
        $.get($this.attr('href'), html => {
            $this.closest('.comment-page-box').html(html);
        });
    });
    $(document).on('click', '[data-type="buy"]', function(e) {
        e.preventDefault();
        buyGoods(goods, $('.info-box .number-input').val() as number);
    }).on('click', '[data-type="addCart"]', function(e) {
        e.preventDefault();
        addToCart(goods, $('.info-box .number-input').val() as number);
    }).on('click', '[data-type="collect"]', function(e) {
        e.preventDefault();
        collectGoods(goods, $(this));
    });
}

function lazyTab(tab: JQuery) {
    const url = tab.data('url');
    if (!url || tab.hasClass('lazy-loading') || tab.hasClass('tab-lazy-loaded')) {
        return;
    }
    tab.addClass('lazy-loading');
    $.get(url, function (html) {
        tab.removeClass('lazy-loading');
        tab.html(html);
        tab.addClass('tab-lazy-loaded');
    });
}

function bindOrder() {
    let replaceUrl = function(url: string, title: string = '订单') {
        partialLoad(box, url, title);
    };
    $('.order-tab a').on('click',function(e) {
        e.preventDefault();
        let $this = $(this);
        $this.addClass('active').siblings().removeClass('active');
        replaceUrl($this.attr('href'), $this.text());
    });
    let box = $('.order-page-box').on('click', '.pagination a', function(e) {
        e.preventDefault();
        let $this = $(this);
        replaceUrl($this.attr('href'), '我的订单第' + $this.text() + '页');
    });
}

function partialLoad(box: JQuery, url: string, title = 'zodream') {
    $.get(url, html => {
        box.html(html);
        history.pushState(null, title, url);
    });
}

function bindCashier() {
    const bindRegion = () => {
        const input = $('.address-edit .region-input');
        if (input.length < 1) {
            return;
        }
        let regionBox = input.multiSelect({
            default: input.data('value') || 0,
            data: BASE_URI + 'region/tree',
            tag: 'region_id'
        });
    };
    const refreshFooter = () => {
        postJson(BASE_URI + 'cashier/preview', pageBox.find('form').serialize(), res => {
            if (res.code !== 200) {
                parseAjax(res);
                return;
            }
            pageBox.find('.checkout-footer').html(res.data.checkout);
        });
    };
    let pageBox = $('.cashier-page').on('click', '.address-edit [data-action="save"]', function() {
        let box = $(this).closest('.panel-body');
        $.post(BASE_URI + 'cashier/save_address', formData(box), (html: string) => {
            if (html.indexOf('false,') === 0) {
                Dialog.tip(html.substr(6));
                return;
            }
            box.html(html);
            refreshFooter();
        });
    }).on('click', '.address-edit [data-action="cancel"]', function() {
        let $this = $(this);
        let box = $this.closest('.panel-body');
        $.post(BASE_URI + 'cashier/address', {
            id: $this.attr('data-prev')
        }, (html: string) => {
            box.html(html);
        });
    }).on('click', '.address-view [data-action="edit"]', function(e) {
        e.preventDefault();
        let $this = $(this);
        let box = $this.closest('.panel-body');
        $.get($this.attr('href'), html => {
            box.html(html);
            bindRegion();
        });
    }).on('click', '.address-view [data-action="dialog"]', function(e) {
        e.preventDefault();
        let $this = $(this);
        let box = $this.closest('.panel-body');
        $.get($this.attr('href'), html => {
            addressDialog.find('.dialog-body').html(html);
            addressDialog.show();
        });
    }).on('click', '.shipping-box [name="shipping"],.payment-box [name="payment"]', function() {
        refreshFooter();
    });
    bindRegion();
    let addressDialog = $('#address-dialog').dialog();
    addressDialog.box.on('click', '.address-item', function() {
        $(this).addClass('active').siblings('.address-item').removeClass('active');
    }).on('click', '.pagination a', function(e) {
        e.preventDefault();
        $.get($(this).attr('href'), html => {
            addressDialog.find('.dialog-body').html(html);
        });
    });
    addressDialog.on('done', function() {
        let item = this.find('.address-item.active');
        if (item.length < 1) {
            Dialog.tip('请选择地址');
            return;
        }
        this.close();
        $.post(BASE_URI + 'cashier/address', {
            id: item.attr('data-id')
        }, (html: string) => {
            pageBox.find('.address-view').closest('.panel-body').html(html);
            refreshFooter();
        });
    });
}