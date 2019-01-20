const CHECKED_CHANGE = 'cart_checked_change';
function addToCart(id: number, amount: number = 1) {
    postJson('cart/add?goods=' + id + '&amount=' + amount, function(data) {
        parseAjax(data);
    });
}

function buyGoods(id: number, amount: number = 1) {
    
}

function collectGoods(id: number, target?: any) {
    postJson('collect/toggle?id=' + id, function(data) {
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
    $(".check-box").click(function() {
        $(this).toggleClass('active').trigger(CHECKED_CHANGE);
    });
    $(".toggle-box").click(function() {
        $(this).toggleClass('active').trigger('change');
    });
    $('.number-box .fa-minus').click(function() {
        let input = $(this).closest('.number-box').find('input'),
            min = input.attr('min') || 0,
            amount = Number(input.val()) || 0;
        if (amount > min) {
            amount -= 1;
        }
        input.val(amount).trigger('change');
    });
    $('.number-box .fa-plus').click(function() {
        let input = $(this).closest('.number-box').find('input'),
            max = input.attr('max') || 999,
            amount = Number(input.val()) || 0;
        if (amount < max) {
            amount += 1;
        }
        input.val(amount).trigger('change');
    });
    $(".cart-footer .btn").click(function(e) {
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
    $(".checkout-footer .btn").click(function(e) {
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
    $(".tab-box .tab-header .tab-item").click(function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.closest(".tab-box").find(".tab-body .tab-item").eq($this.index()).addClass("active").siblings().removeClass("active");
    });
    $(".header-nav>li").mouseenter(function() {
        $(".header-nav .nav-dropdown").hide();
        $(this).find('.nav-dropdown').show();
    });
    $(".scroll-nav .nav-arrow").click(function() {
        $(this).closest('.scroll-nav').toggleClass('unfold');
    });
    $(".header-nav").mouseleave(function() {
        $(this).find('.nav-dropdown').hide();
    });
    $(".header-cart").mouseover(function() {
        $(this).find('.cart-dialog').show();
    }).mouseout(function() {
        $(this).find('.cart-dialog').hide();
    });
    $(".header-search").mouseover(function() {
        $(this).addClass('expanded');
    }).mouseout(function() {
        $(this).removeClass('expanded');
    });
    $('img.lazy').lazyload({callback: 'img'});
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
            search($(this).val());
        }
    });
    $(".header-search .fa-search").click(function() {
        search(searchInput.val());
    });
    $(window).scroll(function() {
        $(".header-main").toggleClass('top-fixed', $(this).scrollTop() > 180);
    }).trigger('scroll');
});

function bindCart(baseUrl: string) {
    $('.number-box input').change(function() {
        let _this = $(this),
            amount = _this.val(),
            box = _this.closest('.cart-item'),
            id = box.attr('data-id');
        $.getJSON(baseUrl + 'cart/update', {
            id: id,
            amount: amount
        }, function(data) {
            parseAjax(data);
        });
    });
    $('.cart-item .fa-trash').click(function() {
        let _this = $(this),
            box = _this.closest('.cart-item'),
            id = box.attr('data-id');
        $.getJSON(baseUrl + 'cart/delete', {
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
        items.each(function() {
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
    $(".category-menu .menu-item").click(function() {
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