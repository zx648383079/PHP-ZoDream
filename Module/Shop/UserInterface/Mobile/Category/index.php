<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '分类';
$js = <<<JS
bindCategory();
JS;
$this->registerJs($js)->extend('../layouts/search');
?>

<div class="has-header has-footer category-page">
    <div class="category-menu">
        <?php foreach($cat_list as $item):?>
        <div class="menu-item"><?=$item->name?></div>
        <?php endforeach;?>
    </div>

    <div class="category-main">
        <?php foreach($cat_list as $item):?>
        <div class="item active lazy-loading" data-url="<?=$this->url('./mobile/category/children', ['id' => $item->id])?>">
        
        </div>
        <?php endforeach;?>
    </div>

</div>

<?php $this->extend('../layouts/navbar');?>