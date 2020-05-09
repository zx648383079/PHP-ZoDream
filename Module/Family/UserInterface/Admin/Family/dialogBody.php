<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;

/** @var $this View */
?>
<?php foreach($model_list as $item):?>
    <div class="family-item" data-id="<?=$item->id?>">
        <?php if($item->clan):?>
            [<?=$item->clan->name?>]
        <?php endif;?>
        <span class="name"><?=$item->name?></span>
    </div>
<?php endforeach; ?>

<?=$model_list->getLink()?>