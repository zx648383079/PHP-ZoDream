<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<div class="product-box">
    <?php foreach($model_list as $item):?>
    <a href="<?=$this->url('./invest', ['id' => $item->id])?>">
        <?=$item->name?>
        <p>收益率<?=$item->earnings?></p>
        <p>周期<?=$item->cycle?>天</p>
    </a>
    <?php endforeach;?>
</div>

<div align="center">
    <?=$model_list->getLink()?>
</div>
