<?php
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Template\View */
?>
<!-- 帮助中心 --->
<div class="helpMap">
    <?php foreach ($this->helpMap as $cat):?>
        <div class="cat">
            <div class="head"><?=Html::a($cat['name'], $cat['url'])?></div>
            <?php foreach ($cat['articles'] as $article):?>
                <div><?=Html::a($article['title'], $article['url'])?></div>
            <?php endforeach;?>
        </div>
    <?php endforeach;?>
</div>
<!-- 友情链接 --->
<div class="links">
    <?php foreach ($this->links as $link):?>
        <a href="<?=$link['url']?>"><img src="<?=$link['ico']?>"><?=$link['name']?></a>
    <?php endforeach;?>
</div>
<!-- 联系方式 --->
<div class="contact">
    <?php foreach ($this->contact as $item):?>
        <a href="<?=$item['url']?>">QQ</a>
    <?php endforeach;?>
</div>
<!-- 底部版权 --->
    <div class="footer text-center">
        <a href="http://www.miitbeian.gov.cn/" target="_blank">湘ICP备16003508号</a>
    </div>
    <script type="text/javascript" src="/assets/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
    <?=$this->footer()?>
</body>
</html>
