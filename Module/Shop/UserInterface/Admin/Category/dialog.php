<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach($model_list as $item):?>
<div class="item item-level-<?=$item['level']?>" data-id="<?=$item['id']?>">
    <?php if($item['level'] > 0):?>
    <span>ￂ<?=str_repeat('ｰ', $item['level'] - 1)?></span>
    <?php endif;?>
    <?=$item['name']?>
</div>
<?php endforeach;?>