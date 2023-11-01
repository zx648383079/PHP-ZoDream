<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '订阅列表';
?>

<div class="panel-container">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>邮箱</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td class="left"><?=$item->email?></td>
                <td>
                    <?php if($item->status < 1):?>
                    待审核
                    <?php endif;?>
                </td>
                <td>
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