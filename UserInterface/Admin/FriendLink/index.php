<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '友情链接申请列表';
?>

<table class="table table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>站点名</th>
        <th>网址</th>
        <th>简介</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->name?></td>
            <td>
                <code><?=$item->url?></code>
            </td>
            <td>
                <?=$this->text($item->brief)?>
            </td>
            <td>
                <?php if($item->status < 1):?>
                待审核
                <?php endif;?>
            </td>
            <td>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>