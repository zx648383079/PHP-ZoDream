<script id="comment_tpl" type="text/template">
    <div class="floor-header">
        <h3>大家都在说</h3>
        <small>生活，没有统一标准</small>
    </div>
    <div class="slider slider-goods" data-height="392" data-width="367">
        <div class="slider-previous">&lt;</div>
        <div class="slider-box">
            <ul>
                {{each comment_goods item}}
                <li class="goods-item">
                    <div class="thumb">
                        <a href="{{ item.goods.url }}"><img src="{{ item.goods.thumb }}" alt=""></a>
                    </div>
                    <div class="comment-item">
                        <div class="item-top">
                            <span>{{ item.user.name }}</span>
                            <span>{{ item.created_at }}</span>
                        </div>
                        <div class="item-middle">
                            <span class="name">{{ item.goods.name }}</span>
                            <span class="price">{{ item.goods.price }}</span>
                        </div>
                        <div class="item-content">
                            {{ item.content }}
                        </div>
                    </div>
                </li>
                {{/each}}
            </ul>
        </div>
        <div class="slider-next">&gt;</div>
    </div>
</script>