<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '配送方式列表';
?>
   <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/shipping/create')?>">新增配送方式</a>
    </div>

    <table class="table table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>说明</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->goods_count?></td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/shipping/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/shipping/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>