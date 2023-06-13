<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '族谱附录';
?>

<div class="page-search-bar">
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/clan/create_meta', compact('clan_id'))?>">新增附录</a>
</div>


<table class="table  table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>附录名</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->name?></td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/clan/edit_meta', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/clan/delete_meta', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>
