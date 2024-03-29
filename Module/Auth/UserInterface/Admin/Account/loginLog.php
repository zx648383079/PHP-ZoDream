<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '登陆记录';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">IP</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="IP">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>登陆账户</th>
            <th>登陆IP</th>
            <th>登陆状态</th>
            <th>登陆方式</th>
            <th>登陆时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$this->text($item->user)?></td>
                <td><?=$item->ip?></td>
                <td><?=$item->status ? '成功' : '失败'?></td>
                <td><?=$item->mode?></td>
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