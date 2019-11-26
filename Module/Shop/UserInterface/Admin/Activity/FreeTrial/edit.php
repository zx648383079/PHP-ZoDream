<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '抽奖';
?>
<h1><?=$this->title?></h1>
<?=Form::open('./@admin/activity/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <?=Form::select('type', ['刮刮乐', '转盘'])?>

    <?=Form::text('覆盖区文字')?>
    <?=Form::text('按钮文字')?>

    <?=Form::text('初始可抽次数')?>
    <?=Form::text('积分可抽次数')?>
    <?=Form::text('兑换所需积分')?>

    <div>
        <a href="">添加奖项</a> <p>最少添加2项，最多添加8项，所有奖项概率之和需为100%</p>
        <table>
            <tr>
                <td>未中奖
                    <input type="text">
                </td>
                <td>
                    获奖概率
                    <input type="text">%
                </td>
                <td>
                    <input type="color">
                </td>
                <td>
                    <i class="fa fa-up"></i>
                    <i class="fa fa-down"></i>
                    <i class="fa fa-times"></i>
                </td>
            </tr>
        </table>
    </div>

    <?=Form::text('start_at', true)?>
    <?=Form::text('end_at', true)?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>