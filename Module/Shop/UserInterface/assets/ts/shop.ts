function addToCart(id: number, amount: number = 1) {
    postJson('cart/add?goods=' + id + '&amount=' + amount);
}

function buyGoods(id: number, amount: number = 1) {
    
}