<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Url\Url;
/** @var $this \Zodream\Domain\View\View */
$this->title = $title;
$this->extend([
    'layout/head',
    'layout/navbar'
]);
?>
<div class="container">
    <?php foreach ($data as $value) :
        if (0 < $value['parent']) break;
        ?>
        <div class="group">
            <div class="heading"><?php echo $value['name'];?></div>
            
            <?php foreach ($data as $item) :
                if (0 >= $item['parent'] || $item['parent'] != $value['id']) continue;
                ?>
                <div class="col-md-3">
                    <h3><a href="<?=Url::to(['forum/thread', 'id' => $item['id']]);?>">
                            <?=$item['name'];?></a></h3>
                </div>
            <?php endforeach;?>
        </div>
    <?php endforeach;?>
</div>


<?php $this->extend('layout/foot')?>