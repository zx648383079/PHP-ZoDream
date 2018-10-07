<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '文章列表';
?>
<div class="search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./admin/article/create')?>">新增文章</a>
</div>

<table class="table table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>标题</th>
        <th>分类</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->title?></td>
            <td><?=$item->category->name?></td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./admin/article/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/article/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>