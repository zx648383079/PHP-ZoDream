<script id="recommend_tpl" type="text/template">
    <div class="panel">
        <div class="panel-header">
            大家都在看
        </div>
        <div class="panel-body">
            <div class="slider slider-goods" data-height="279" data-width="210">
                <div class="slider-previous">&lt;</div>
                <div class="slider-box">
                    <ul>
                        {{each goods_list goods}}
                        <li class="goods-item">
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
        </div>
    </div>
</script>