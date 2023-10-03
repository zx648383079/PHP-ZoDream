<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '便签列表';
$this->registerCssFile('@note.css')
->registerJsFile('@note.min.js')
    ->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./@admin/', false)), View::HTML_HEAD)
?>

<div class="page-search-bar">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">内容</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="内容">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
</div>
<?php foreach($model_list as $item):?>
<div class="note-row">
    <div class="item-content">
        <?=$item->html?>
    </div>
    <div class="item-time">
        <span><?=$item->user ? $item->user->name : ''?></span>
        <span><?=$item->date?></span>
    </div>
    <a data-type="del" href="<?=$this->url('./@admin/note/delete', ['id' => $item->id])?>" class="fa fa-times"></a>
</div>
<?php endforeach;?>
<div align="center">
    <?=$model_list->getLink()?>
</div>