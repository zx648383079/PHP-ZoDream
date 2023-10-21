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
            <th>操作</th>
            <th>备注</th>
            <th>IP</th>
            <th>操作时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->action?></td>
                <td><?=$item->remark?></td>
                <td><?=$item->ip?></td>
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