<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'配置项目';

$js = <<<JS
    var start_at = $('[name=start_at]').datetimer();
    $('[name=end_at]').datetimer({
        min: start_at
    });
JS;


$this->registerJs($js, View::JQUERY_READY);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './money/save_project')?>
    <?=Form::text('name', true)?>
    <?=Form::text('alias')?>
    <?=Form::text('money', true)?>
    <?=Form::select('account_id', [$account_list])?>
    <?=Form::select('product_id', [$product_list])?>
    <?=Form::text('earnings')?>
    <?=Form::text('start_at')?>
    <?=Form::text('end_at')?>
    <?=Form::textarea('remark')?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>

