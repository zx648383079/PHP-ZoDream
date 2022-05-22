<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '文章分类列表';
?>

<div class="page-search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/category/create')?>">新增分类</a>
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
    <?php foreach($items as $item):?>
        <tr>
            <td><?= $item['id']?></td>
            <td class="tree-item">
                <?php if($item['level'] > 0):?>
                <span>ￂ<?=str_repeat('ｰ', $item['level'] - 1)?></span>
                <?php endif;?>
                <a href="<?=$this->url('./@admin/post', ['cat_id' => $item['id']])?>"><?=$item['name']?></a>
            </td>
            <td><?=0//$item['post_count']?></td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/post', ['cat_id' =>  $item['id']])?>">查看</a>
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/category/edit', ['id' =>  $item['id']])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/category/delete', ['id' =>  $item['id']])?>">删除</a>
                </div>
                
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
