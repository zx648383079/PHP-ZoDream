<header class="top top-search-box">
    <a href="<?=$this->url('./mobile')?>" class="logo">
        <img src="/assets/images/wap_logo.png" alt="">
    </a>
    <a class="search-entry" href="<?=$this->url('./mobile/search')?>">
        <i class="fa fa-search"></i>
        <span>搜索商品, 共19304款好物</span>
    </a>
    <?php if(auth()->guest()):?>
        <a href="<?=$this->url('./mobile/member/login')?>">登录</a>
    <?php else:?>
        <a href="<?=$this->url('./mobile/member/message')?>">
            <i class="fa fa-comment-dots"></i>
        </a>
    <?php endif;?>
</header>