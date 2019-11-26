<script id="best_tpl" type="text/template">
    <div class="floor-header">
        <h3>人气推荐</h3>
        <small>编辑推荐</small>
        <small>热销总榜</small>
        <a href="" class="header-right">更多推荐 &gt;</a>
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