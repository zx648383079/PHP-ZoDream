<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
/** @var $this View */

$this->title = '新增一日三餐';

$js = <<<JS
    $('[name=day]').datetimer({
        format: 'y-mm-dd'
    });
JS;


$this->registerJs($js, View::JQUERY_READY);
?>

<h1><?=$this->title?></h1>
<?=Form::open('./income/save_day_log')?>
    <?=Theme::text('day', date('Y-m-d'), '日期', '', true)?>
    <?=Theme::select('account_id', [$account_list], null, '账户')?>
    <?=Theme::select('channel_id', [$channel_list, ['请选择']], null, '渠道')?>
    <?=Theme::select('budget_id', [$budget_list, ['请选择']], null, '预算')?>
    <div class="header">早餐</div>
    <?=Theme::text('breakfast[time]', '09:00:00', '时间')?>
    <?=Theme::text('breakfast[money]', '', '金额')?>
    <?=Theme::textarea('breakfast[remark]', '早餐', '备注')?>
    <div class="header">午餐</div>
    <?=Theme::text('lunch[time]', '12:00:00', '时间')?>
    <?=Theme::text('lunch[money]', '', '金额')?>
    <?=Theme::textarea('lunch[remark]', '午餐', '备注')?>
    <div class="header">晚餐</div>
    <?=Theme::text('dinner[time]', '20:00:00', '时间')?>
    <?=Theme::text('dinner[money]', '', '金额')?>
    <?=Theme::textarea('dinner[remark]', '晚餐', '备注')?>
   
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消保存</a>
    </div>
<?= Form::close() ?>