<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '资源管理';
?>

<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>图文及媒体素材</li>
    </ul>
    <span class="toggle"></span>
</div>
<div class="page-action">
    <?php if(!$type || $type == 'news'):?>
        <a href="<?=$this->url('./admin/media/create')?>">添加图文</a>
    <?php else:?>
        <a href="<?=$this->url('./admin/media/create', ['type' => 'media'])?>">添加素材</a>
    <?php endif;?>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>类型</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($model_list as $item):?>
           <tr>
                <td><?=$item->id?></td>
                <td><?=$item->title?></td>
                <td><?=$item->type?></td>
                <td><?=$item->media_id ? '已同步' : '未同步'?></td>
                <td>
                    <?php if(empty($item->media_id) && $item->parent_id < 1):?>
                    <a href="<?=$this->url('./admin/media/async', ['id' => $item->id])?>">同步</a>
                    <?php endif;?>
                    <a href="<?=$this->url('./admin/media/edit', ['id' => $item->id])?>">编辑</a>
                    <a data-type="del" href="<?=$this->url('./admin/media/delete', ['id' => $item->id])?>">删除</a>
                </td>
           </tr>
        <?php endforeach;?>
    </tbody>
</table>
