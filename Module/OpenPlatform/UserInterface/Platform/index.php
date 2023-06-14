<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '管理应用';
$status_list = [0 => '无', 1 => '正常', 9 => '审核中'];
?>
<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">名称</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="名称">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./platform/create')?>">新增应用</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>应用名</th>
            <th>APP ID</th>
            <th>状态</th>
            <th>时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr class="status-<?=intval($item->status)?>">
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td>
                    <?=$item->appid?>
                </td>
                <td>
                    <?=$status_list[intval($item->status)]?>
                </td>
                <td>
                    <?=$item->created_at?>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./platform/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./platform/delete', ['id' => $item->id])?>">删除</a>
                    </div>
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