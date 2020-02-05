<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '文章分类列表';
$canDo = auth()->user()->isAdministrator();
?>

<div class="page-search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/term/create')?>">新增分类</a>
</div>

<table class="table  table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>分类名</th>
        <th>统计</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($term_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->name?></td>
            <td><?=$item->blog_count?></td>
            <td>
                <?php if($canDo):?>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/blog', ['term_id' => $item->id])?>">查看</a>
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/term/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/term/delete', ['id' => $item->id])?>">删除</a>
                </div>
                <?php endif;?>
                
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
