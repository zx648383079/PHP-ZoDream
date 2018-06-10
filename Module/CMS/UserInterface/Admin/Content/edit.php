<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '编辑文章';
$js = <<<JS
    var ue = UE.getEditor('container');
JS;

$this->registerJsFile('/assets/ueditor/ueditor.config.js')
    ->registerJsFile('/assets/ueditor/ueditor.all.js')
    ->registerJs($js);
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/goods/save')?>" method="post" class="form-table" role="form">
        <div class="zd-tab">
            <div class="zd-tab-head">
                <div class="zd-tab-item active">
                    基本
                </div>
                <div class="zd-tab-item">
                    高级
                </div>
            </div>
            <div class="zd-tab-body">
                <div class="zd-tab-item active">
                    <?php foreach($field_list as $item):?>
                       <?php if($item->is_default):?>
                          <?=$model->toInput($model->{$item->name})?>
                       <?php endif;?>
                    <?php endforeach;?>
                </div>
                <div class="zd-tab-item">
                    <?php foreach($field_list as $item):?>
                       <?php if(!$item->is_default):?>
                        <?=$model->toInput($model->getExtendValue($item->name))?>
                       <?php endif;?>
                    <?php endforeach;?>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
        <input type="hidden" name="cat_id" value="<?=$model->cat_id?>">
    </form>
