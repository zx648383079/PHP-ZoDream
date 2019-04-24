<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '标签列表';
$canDo = auth()->user()->isAdministrator();
?>

<div class="search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./admin/tag/create')?>">新增标签</a>
</div>

<table class="table  table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>标签名</th>
        <th>统计</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->name?></td>
            <td><?=$item->blog_count?></td>
            <td>
                <?php if($canDo):?>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./admin/tag/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/tag/delete', ['id' => $item->id])?>">删除</a>
                </div>
                <?php endif;?>
                
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>