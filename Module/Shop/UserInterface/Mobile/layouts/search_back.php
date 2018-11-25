<header class="top">
    <div class="search-box under-search">
        <a class="home-btn" href="<?=$this->url('./mobile')?>">
            <i class="fa fa-home"></i>
        </a>
        <form action="<?=$this->url('./mobile/search')?>">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" name="keywords" value="<?=$this->keywords?>" placeholder="搜索">
            <i class="fa fa-times-circle"></i>
        </form>
    </div>
</header>