<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '标签列表';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/tag/create')?>">新增标签</a>
    </div>

    <table class="table  table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>标签名</th>
            <th>统计</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->blog_count?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/tag/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/tag/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($items->isEmpty()):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
    <div align="center">
        <?=$items->getLink()?>
    </div>
</div>