<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $model->id > 0 ? '表单编辑' : '新增表单';
?>

<h1><?=$this->title?></h1>
<form data-type="ajax" action="<?=$this->url('./@admin/form/save')?>" method="post" class="form-table" role="form">
    <div class="tab-box">
        <div class="tab-header">
            <?php foreach($tab_list as $item):?>
            <div class="tab-item<?=$item['active'] ? ' active' : ''?>">
                <?=$item['name']?>
            </div>
            <?php endforeach;?>
        </div>
        <div class="tab-body">
        <?php foreach($tab_list as $item):?>
            <div class="tab-item<?=$item['active'] ? ' active' : ''?>">
                <?php foreach($item['items'] as $item):?>
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
    <input type="hidden" name="model_id" value="<?=$model_id?>">
</form>
