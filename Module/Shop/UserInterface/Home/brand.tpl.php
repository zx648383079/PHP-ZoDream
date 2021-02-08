<script id="brand_tpl" type="text/template">
    <div class="floor-header">
        <h3>Featured Brands</h3>
        <small></small>
        <a href="" class="header-right">More &gt;</a>
    </div>
    <div class="floor-grid">
        {{each brand_list item}}
        <a href="{{ item.url }}">
            <div class="name">{{ item.name }}</div>
            <div class="price">{{ item.price }}</div>
            <img src="{{ item.image }}" alt="">
        </a>
        {{/each}}
    </div>
</script>