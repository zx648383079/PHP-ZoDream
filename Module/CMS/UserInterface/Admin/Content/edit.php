<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '编辑文章';
?>

<h1><?=$this->title?></h1>
<form data-type="ajax" action="<?=$this->url('./admin/content/save')?>" method="post" class="form-table" role="form">
    <div class="zd-tab">
        <div class="zd-tab-head">
            <?php foreach($tab_list as $key => $item):?>
            <div class="zd-tab-item<?=$item['active'] ? ' active' : ''?>">
                <?=$key?>
            </div>
            <?php endforeach;?>
        </div>
        <div class="zd-tab-body">
        <?php foreach($tab_list as $key => $item):?>
            <div class="zd-tab-item<?=$item['active'] ? ' active' : ''?>">
                <?php foreach($item['fields'] as $item):?>
                    <?=$scene->toInput($item, $data)?>
                <?php endforeach;?>
            </div>
            <?php endforeach;?>
        </div>
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="id" value="<?=$id?>">
    <input type="hidden" name="cat_id" value="<?=$cat_id?>">
    <input type="hidden" name="parent_id" value="<?=$data['parent_id']?>">
</form>
