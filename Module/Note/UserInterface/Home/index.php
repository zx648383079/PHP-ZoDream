<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '便签';
if (!auth()->guest()) {
    $url = $this->url('./note/save');
    $js = <<<JS
    bindNewNote('{$url}');
JS;
    $this->registerJs($js);
}
$this->extend('layouts/main');
?>

<div class="flex-box">
    <?php if(!auth()->guest()):?>
    <div class="item new-item">
        <div class="item-content">
            <textarea placeholder="请输入内容" max-length="255"></textarea>
        </div>
        <div class="item-action">
            <span class="length-box">0/255</span>
            <div class="pull-right">
                <i class="visbile-toggle fa fa-eye"></i>
                <i class="fa fa-check"></i>
            </div>
        </div>
    </div>
    <?php endif;?>
    <?php $this->extend('./page');?>
</div>
<?php if($model_list->hasMore()):?>
<div class="more-load" data-page="<?=$model_list->getIndex()?>" data-target=".flex-box" data-url="<?=$this->url(['page' => null])?>">
加载中。。。
</div>
<?php endif;?>