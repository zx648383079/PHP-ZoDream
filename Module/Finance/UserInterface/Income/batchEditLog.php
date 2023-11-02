<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = '批量编辑流水';
?>

<h1><?=$this->title?></h1>
<?=Form::open('./income/save_batch_log')?>
    <?=Form::text('keywords')->label('关键字')?>
    <?=Form::select('account_id', [$account_list, ['请选择']])->label('账户')?>
    <?=Form::select('project_id', [$project_list, ['请选择']])->label('项目')?>
    <?=Form::select('channel_id', [$channel_list, ['请选择']])->label('渠道')?>
    <?=Form::select('budget_id', [$budget_list, ['请选择']])->label('预算')?>
   
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认编辑</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close() ?>