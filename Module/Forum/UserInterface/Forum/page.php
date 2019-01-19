<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@forum.css')
    ->registerJsFile('@forum.min.js');
?>

<?php foreach($thread_list as $item):?>
    <div class="thread-item">
        <div class="name">
            <i class="fa fa-file"></i>
            <?php if($item->classify):?>
            [
            <a href="<?=$this->url('./forum', ['id' => $item->forum_id, 'classify' => $item->classify_id])?>"><?=$item->classify->name?></a>
            ]
            <?php endif;?>
            <a href="<?=$this->url('./thread', ['id' => $item->id])?>"><?=$item->title?></a>
        </div>
        <div class="time">
            <em><?=$item->user->name?></em>
            <em><?=$item->updated_at?></em>
        </div>
        <div class="count">
            <em>1</em>
            <em>2</em>
        </div>
        <div class="reply">
            <em>admin</em>
            <em>1分钟</em>
        </div>
    </div>
<?php endforeach;?>
<div class="paging-box">
    <?=$thread_list->getLink()?>
</div>
