<script id="best_tpl" type="text/template">
    <div class="floor-header">
        <h3>Featured Products</h3>
        <small></small>
        <small></small>
        <a href="" class="header-right">More &gt;</a>
    </div>
    <div class="first-goods-list">
        {{each new_goods goods}}
        <a href="{{ goods.url }}" class="goods-item">
            <div class="thumb">
                <img src="{{ goods.thumb }}" alt="">
            </div>
            <div class="name">{{ goods.name }}</div>
            <div class="price">{{ goods.price }}</div>
        </a>
        {{/each}}
    </div>
</script>