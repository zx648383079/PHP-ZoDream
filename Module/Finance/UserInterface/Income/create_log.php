<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'流水';

$js = <<<JS
    $('[name=happened_at]').datetimer();
JS;


$this->registerJs($js, View::JQUERY_READY);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './income/save_log')?>
    <?=Form::radio('type', ['支出', '收入', '借出', '借入'])?>
    <?=Form::text('money', true)?>
    <?=Form::text('frozen_money')?>
    <?=Form::select('account_id', [$account_list])?>
    <?=Form::select('project_id', [$project_list, ['请选择']])?>
    <?=Form::select('channel_id', [$channel_list, ['请选择']])?>
    <?=Form::select('budget_id', [$budget_list, ['请选择']])?>
    <?=Form::text('trading_object')?>
    <?=Form::text('happened_at', true)?>
    <?=Form::textarea('remark')?>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
    <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
<?= Form::close('id') ?>