<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $model->id > 0 ? '文章编辑' : '新增文章';
?>

<h1><?=$this->title?></h1>
<form data-type="ajax" action="<?=$this->url('./@admin/content/save')?>" method="post" class="form-table" role="form">
    <div class="zd-tab">
        <div class="zd-tab-head">
            <?php foreach($tab_list as $item):?>
            <div class="zd-tab-item<?=$item['active'] ? ' active' : ''?>">
                <?=$item['name']?>
            </div>
            <?php endforeach;?>
        </div>
        <div class="zd-tab-body">
        <?php foreach($tab_list as $group):?>
            <div class="zd-tab-item<?=$group['active'] ? ' active' : ''?>">
                <?php foreach($group['items'] as $item):?>
                    <?=$scene->toInput($item, $data)?>
                <?php endforeach;?>
            </div>
            <?php endforeach;?>
        </div>
    </div>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
    <input type="hidden" name="id" value="<?=$id?>">
    <input type="hidden" name="cat_id" value="<?=$cat_id?>">
    <input type="hidden" name="model_id" value="<?=$model->id?>">
    <input type="hidden" name="parent_id" value="<?=$data['parent_id']?>">
</form>
