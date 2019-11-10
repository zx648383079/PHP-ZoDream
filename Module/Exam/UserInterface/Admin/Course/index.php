<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '科目列表';
?>
<div class="search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/course/create')?>">新增科目</a>
</div>

<table class="table table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>分类名</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item['id']?></td>
            <td class="tree-item">
                <?php if($item['level'] > 0):?>
                <span>ￂ<?=str_repeat('ｰ', $item['level'] - 1)?></span>
                <?php endif;?>
                <a href="<?=$this->url('./@admin/question', ['course' => $item['id']])?>"><?=$item['name']?></a>
            </td>
            <td><?=$this->text($item['description'], 20)?></td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/question', ['course' => $item['id']])?>">查看</a>
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/course/edit', ['id' => $item['id']])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/course/delete', ['id' => $item['id']])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>