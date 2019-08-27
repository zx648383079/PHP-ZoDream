<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '抽奖';
?>
<h1><?=$this->title?></h1>
<?=Form::open('./admin/activity/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>

    <div class="group-box">
        <p class="text-center">组合明细</p>
        <table class="regional-table">
            <thead>
            <tr>
                <th width="42%">商品名称</th>
                <th>
                    组合数量
                </th>
                <th>分摊价格</th>
                <th>
                    成本价
                </th>
                <th>小计</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        111
                    </td>
                    <td>
                        <input type="text">
                    </td>
                    <td>
                        <input type="text">
                    </td>
                    <td>11</td>
                    <td>211</td>
                    <td>
                        <a href="">删除</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <?=Form::text('组合价格')?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>