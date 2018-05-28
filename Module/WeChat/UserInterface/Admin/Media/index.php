<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '资源管理';
?>

<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>关注公众号时自动回复</li>
    </ul>
    <span class="toggle"></span>
</div>
<div class="page-action">
    <?php if(!$type || $type == 'news'):?>
        <a href="<?=$this->url('./admin/media/create')?>">添加图文</a>
    <?php endif;?>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>类型</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($model_list as $item):?>
           <tr>
                <td><?=$item->id?></td>
                <td><?=$item->title?></td>
                <td><?=$item->type?></td>
                <td>
                    <a href="<?=$this->url('./admin/media/edit', ['id' => $item->id])?>">编辑</a>
                    <a data-type="del" href="<?=$this->url('./admin/media/delete', ['id' => $item->id])?>">删除</a>
                </td>
           </tr>
        <?php endforeach;?>
    </tbody>
</table>
