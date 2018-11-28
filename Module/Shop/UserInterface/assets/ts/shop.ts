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

}
function search(keywords: string) {
    window.location.href = $(".header-search").data('url') + '?keywords='+ keywords;
}
$(function() {
    $(".check-box").click(function() {
        $(this).toggleClass('active').trigger('change');
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
    $(".tab-box .tab-header .tab-item").click(function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.closest(".tab-box").find(".tab-body .tab-item").eq($this.index()).addClass("active").siblings().removeClass("active");
    });
    $(".header-nav>li").mouseenter(function() {
        $(".header-nav .nav-dropdown").hide();
        $(this).find('.nav-dropdown').show();
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
    $(".cart-item .check-box").change(function() {
        refreshCart();
    });
    $(".cart-footer .check-box").change(function() {
        $(".cart-item .check-box").toggleClass('active', $(this).hasClass('active'));
        refreshCart();
    })
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