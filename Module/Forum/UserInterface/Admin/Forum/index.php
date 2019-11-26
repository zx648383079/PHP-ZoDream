<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '板块列表';
?>
<div class="search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/forum/create')?>">新增板块</a>
</div>

<table class="table table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>板块</th>
        <th>统计</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($forum_list as $item):?>
        <tr>
            <td><?=$item['id']?></td>
            <td class="tree-item">
                <?php if($item['level'] > 0):?>
                <span>ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                <?php endif;?>
                <a href="<?=$this->url('./@admin/thread', ['forum_id' => $item['id']])?>"><?=$item['name']?></a>
            </td>
            <td></td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/thread', ['forum_id' => $item['id']])?>">查看</a>
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/forum/edit', ['id' => $item['id']])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/forum/delete', ['id' => $item['id']])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>