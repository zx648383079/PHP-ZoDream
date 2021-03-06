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
                <a href="<?=$item->url?>" target="_blank" rel="noopener noreferrer" class="fa fa-globe"></a>
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
                <?php if($item->status < 1):?>
                <a class="btn btn-danger" data-type="del" data-tip="确认审核通过此友情链接？" href="<?=$this->url('./@admin/friend_link/verify', ['id' => $item->id])?>">审核</a>
                <?php else:?>
                <a class="btn" data-type="del" data-tip="确认下架此友情链接？" href="<?=$this->url('./@admin/friend_link/remove', ['id' => $item->id])?>">下架</a>
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>