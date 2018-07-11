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