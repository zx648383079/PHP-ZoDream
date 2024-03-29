<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '留言反馈列表';
?>

<div class="panel-container">
    <table class="table table-toggle table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>称呼</th>
            <th>邮箱</th>
            <th>联系方式</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td class="left"><?=$item->name?></td>
                <td class="right">
                    <?=$item->email?>
                </td>
                <td class="right">
                    <?=$item->phone?>
                </td>
                <td>
                    <?php if($item->status < 1):?>
                    待审核
                    <?php endif;?>
                </td>
                <td>
                </td>
            </tr>
            <tr class="tr-child">
                <td colspan="6">
                    <?=$this->text($item->content)?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($model_list->isEmpty()):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>
</div>