<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '发送记录';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">关键字</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="关键字">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>收件人</th>
            <th>模板编号</th>
            <th>状态</th>
            <th>回执</th>
            <th>操作时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->target?></td>
                <td><?=$item->template_name?></td>
                <td><?=$item->status == 4 ? '失败' : '成功'?></td>
                <td>
                    <?php if($item->status == 4):?>
                    错误信息：
                    <?php endif;?>
                    <?=$item->message?>
                </td>
                <td><?=$item->created_at?></td>
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