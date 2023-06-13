<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '模块列表';
?>

<div class="panel-container">
    <div class="page-search-bar">

        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/model/create')?>">新增模块</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>名称</th>
            <th>表名</th>
            <th>类型</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td><?=$item->name?></td>
                <td>
                    <?=$item->table?>
                </td>
                <td>
                    <?=$item->type > 0 ? '表单' : '实体'?>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary" href="<?=$this->url('./@admin/model/field', ['id' => $item->id])?>">模块字段</a>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/model/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/model/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach?>
        </tbody>
    </table>
    <?php if(empty($model_list)):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
</div>

