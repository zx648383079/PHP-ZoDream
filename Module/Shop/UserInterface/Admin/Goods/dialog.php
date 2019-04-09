<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach($model_list as $item):?>
<div class="item" data-id="<?=$item->id?>">
    <div class="thumb">
        <img src="<?=$item->thumb?>" alt="">
    </div>
    <div class="name"><?=$item->name?></div>
    <div class="price"><?=$item->price?></div>
</div>
<?php endforeach;?>
<div>
<?=$model_list->getLink()?>
</div>