<script id="new_tpl" type="text/template">
    <div class="floor-header">
        <h3>New Products</h3>
        <small></small>
        <a href="" class="header-right">More &gt;</a>
    </div>
    <div class="slider slider-goods" data-height="344">
        <div class="slider-previous">&lt;</div>
        <div class="slider-box">
            <ul>
                {{each new_goods goods}}
                <li class="goods-item item-hover">
                    <div class="thumb">
                        <a href="{{ goods.url }}">
                            <img src="{{ goods.thumb }}" alt="">
                        </a>
                    </div>
                    <div class="name">{{ goods.name }}</div>
                    <div class="price">{{ goods.price }}</div>
                </li>
                {{/each}}
            </ul>
        </div>
        <div class="slider-next">&gt;</div>
    </div>
</script>