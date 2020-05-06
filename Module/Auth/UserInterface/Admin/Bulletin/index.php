<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '所有通知';
$js = <<<JS
bindBulletin();
JS;
$this->registerJs($js);
?>

<div class="page-search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">标题</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" data-type="del" data-tip="确定标记所有未读消息" href="<?=$this->url('./@admin/bulletin/read_all')?>">全部已读</a>
</div>

<?php foreach($model_list as $item):?>
<div class="bulletin-item<?=$item->status > 0 ? ' min' : ''?>">
    <div class="title">
        <?=$item->bulletin->title?>
        <?php if($item->status < 1):?>
        <a href="<?=$this->url('./@admin/bulletin/read', ['id' => $item->bulletin_id])?>" class="btn">查看</a>
        <?php endif;?>
    </div>
    <div class="content"><?=$item->bulletin->content?></div>
    <div class="footer">
        <span>发送者：<?=$item->bulletin->user_name?></span>
        <span>发送时间：<?=$item->created_at?></span>
        <span>状态：<?=$item->status > 0 ? '已阅' : '未读'?></span>
    </div>
</div>
<?php endforeach;?>

<div align="center">
    <?=$model_list->getLink()?>
</div>