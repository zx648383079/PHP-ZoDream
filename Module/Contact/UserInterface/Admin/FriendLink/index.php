<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '友情链接申请列表';
?>

<div class="panel-container">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>站点名</th>
            <th>网址</th>
            <th class="auto-hide">简介</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$this->text($item->name)?></td>
                <td>
                    <code><?=$item->url?></code>
                    <a href="<?=$item->url?>" target="_blank" rel="noopener noreferrer" class="fa fa-globe"></a>
                </td>
                <td class="auto-hide">
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
                    <a class="btn btn-info" data-type="del" data-tip="确认下架此友情链接？" href="<?=$this->url('./@admin/friend_link/remove', ['id' => $item->id])?>">下架</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($model_list->isEmpty()):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>
</div>