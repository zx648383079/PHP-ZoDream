<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '广告位列表';
?>
   <div class="page-search">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/ad/create_position')?>">新增广告位</a>
    </div>

    <table class="table table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>广告位</th>
            <th>统计</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td>0</td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/ad', ['position_id' => $item->id])?>">查看</a>
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/ad/edit_position', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/ad/delete_position', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>