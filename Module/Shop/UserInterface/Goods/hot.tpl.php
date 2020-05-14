<script id="hot_tpl" type="text/template">
    <div class="panel">
        <div class="panel-header">
            TOP/24
        </div>
        <div class="panel-body">
            {{each goods_list goods}}
            <a href="{{ goods.url }}" class="goods-item">
                <div class="thumb">
                    <img src="{{ goods.thumb }}" alt="">
                </div>
                <div class="name">{{ goods.name }}</div>
                <div class="price">{{ goods.price }}</div>
            </a>
            {{/each}}
        </div>
    </div>
</script>