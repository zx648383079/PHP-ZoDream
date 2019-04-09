<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach($model_list as $item):?>
<div class="item" data-id="<?=$item->id?>">
    <?=$item->name?>
</div>
<?php endforeach;?>
<div>
<?=$model_list->getLink()?>
</div>