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
    $(".goods-item").each(function () {
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

$(function() {
    $(".check-box").click(function() {
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
            max = input.attr('max') || 1,
            amount = Number(input.val()) || 0;
        if (amount < max) {
            amount += 1;
        }
        input.val(amount).trigger('change');
    });
});