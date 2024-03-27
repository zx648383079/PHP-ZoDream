<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
/** @var $page \Zodream\Html\Page */
$this->title = 'Overview';
?>

<?php foreach($items as $item):?>
<a class="column-item" title="点击查看详情">
    <div class="icon">
        <i class="fa <?= empty($item['icon']) ? 'fa-file' : $item['icon'] ?>"></i>
    </div>
    <div class="content">
        <h3><?= $item['name'] ?></h3>
        <p>
            <?= $item['count'] ?>
            <?php if(!empty($item['inc'])):?>
            <span class="item-tag fa <?= $item['inc'] > 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></span>
            <?php endif;?>
            <?php if(!empty($item['unit'])):?>
            <span class="item-unit"><?= $item['unit'] ?></span>
            <?php endif;?>
            
        </p>
    </div>
</a>
<?php endforeach;?>
