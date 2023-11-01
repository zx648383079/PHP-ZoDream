<?php
defined('APP_DIR') or exit();

use Module\WeChat\Domain\EditorInput;
use Zodream\Template\View;
/** @var $this View */
$this->title = '消息管理';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>关注公众号时自动回复</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-primary pull-right" href="<?=$this->url('./@admin/reply/add')?>">添加</a>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <td>ID</td>
                <td>事件</td>
                <td>响应类型</td>
                <td>关键字</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
        <?php foreach($reply_list as $item): ?>
            <tr>
                <td><?=$item->id?></td>
                <td>
                    <?=$event_list[$item->event]?>
                </td>
                <td>
                    <?=EditorInput::$type_list[$item->type]?>
                </td>
                <td>
                    <?=$item->keywords?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/reply/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/reply/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach?>
        </tbody>
    </table>
    <?php if($reply_list->isEmpty()):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
    <div align="center">
        <?=$reply_list->getLink()?>
    </div>
</div>
