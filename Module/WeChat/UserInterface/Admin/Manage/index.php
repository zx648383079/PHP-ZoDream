<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '公众号管理';
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>公众号管理</li>
        <li>必须先选择公众号，才能管理公众下面的其他内容</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<div class="page-action">
    <a href="<?=$this->url('./@admin/manage/create')?>">添加</a>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>类型</th>
            <th>APPID</th>
            <th>说明</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($model_list as $item):?>
           <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->type_label?></td>
                <td><?=$item->appid?></td>
                <td><?=$item->description?></td>
                <td><?=$item->status_label?></td>
                <td>
                    <?php if($item->id != $current_id):?>
                        <a href="<?=$this->url('./@admin/manage/change', ['id' => $item->id])?>">管理</a>
                    <?php else:?>
                        <a class="active" href="javascript:;">管理中</a>
                    <?php endif;?>
                    <a href="<?=$this->url('./@admin/manage/edit', ['id' => $item->id])?>">编辑</a>
                    <a data-type="del" href="<?=$this->url('./@admin/manage/delete', ['id' => $item->id])?>">删除</a>
                </td>
           </tr>
        <?php endforeach;?>
    </tbody>
</table>
