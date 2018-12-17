<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<?php foreach($model_list as $item):?>
<div class="item">
    <div class="item-content">
        <?=$item->html?>
    </div>
    <div class="item-time">
        <span><?=$item->user ? $item->user->name : ''?></span>
        <span><?=$item->date?></span>
    </div>
    <?php if(!auth()->guest()):?>
    <a href="<?=$this->url('./note/delete', ['id' => $item->id])?>" class="fa fa-times"></a>
    <?php endif;?>
</div>
<?php endforeach;?>