<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '抽奖';
$js = <<<JS
bindLottery();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open('./@admin/activity/lottery/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <?=Form::select('scope_type', ['刮刮乐', '转盘'])->label('抽奖形式')?>

    <?=Form::text('configure[over_text]')->label('覆盖区文字')?>
    <?=Form::text('configure[btn_text]')->label('按钮文字')?>

    <?=Form::text('configure[start_times]')->label('初始可抽次数')?>
    <?=Form::text('configure[buy_times]')->label('积分可抽次数')?>
    <?=Form::text('configure[time_price]')->label('兑换所需积分')?>

    <div class="items-table">
        <a href="javascript:;" data-action="add">添加奖项</a> <p>最少添加2项，最多添加8项，所有奖项概率之和需为100%</p>
        <table>
            <tr>
                <td>未中奖
                    <input type="text" name="configure[items][name][]" >
                </td>
                <td>
                    获奖概率
                    <input type="text" name="configure[items][chance][]" size="10">%
                </td>
                <td>
                    <input type="color" name="configure[items][color][]">
                </td>
                <td>
                    <i class="fa fa-arrow-up" data-action="up"></i>
                    <i class="fa fa-arrow-down" data-action="down"></i>
                    <i class="fa fa-times" data-action="del"></i>
                </td>
            </tr>
        </table>
    </div>

    <div class="input-group">
        <label for="start_at">起止时间</label>
        <div class="">
            <input type="text" id="start_at" class="form-control " name="start_at" autocomplete="off" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            ~
            <input type="text" id="end_at" class="form-control " name="end_at" placeholder="请输入结束时间" autocomplete="off" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>