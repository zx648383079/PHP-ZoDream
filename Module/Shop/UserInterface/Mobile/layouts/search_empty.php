<header class="top">
    <div class="search-box">
        <form action="<?=$this->url('./mobile/search')?>">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" name="keywords" value="<?=$this->keywords?>" placeholder="搜索">
            <i class="fa fa-times-circle"></i>
        </form>
        <a class="cancel-btn" href="javascript:history.back();">取消</a>
    </div>
</header>