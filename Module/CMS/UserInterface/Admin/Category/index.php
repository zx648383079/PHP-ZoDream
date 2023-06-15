<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/category/create')?>">新增栏目</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>栏目名</th>
            <th>分组</th>
            <th>统计</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item['id']?></td>
                <td class="text-left">
                    <?php if($item['level'] > 0):?>
                    <span>ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                    <?php endif;?>
                    <a href="<?=$this->url('./category', ['id' => $item['id']])?>"><?=$item['title']?></a>
                </td>
                <td><?=$item['groups']?></td>
                <td><?=intval($item['content_count'])?></td>
                <td class="text-right">
                    <div class="btn-group">
                        <?php if($item['type'] < 1):?>
                        <a class="btn btn-primary" href="<?=$this->url('./@admin/content', ['cat_id' => $item['id']])?>">查看</a>
                        <?php endif;?>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/category/edit', ['id' => $item['id']])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/category/delete', ['id' => $item['id']])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if(empty($model_list)):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
</div>