<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '用户管理';
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>关注用户管理</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-primary pull-right" data-type="ajax" href="<?=$this->url('./@admin/user/refresh')?>">同步</a>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>微信ID</th>
                <th>昵称</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->openid?></td>
                <td><?=$item->nickname ?? ''?></td>
                <td><?=$item->status > 0 ? '已关注' : '未关注' ?></td>
                <td>
                    <div class="btn-group">
                        <?php if($item->status === 0):?>
                        <a href="<?=$this->url('./@admin/reply/all', ['user_id' => $item->id])?>" class="btn btn-default">群发消息</a>
                        <?php endif;?>
                        <a data-type="del" href="<?=$this->url('./@admin/user/delete', ['id' => $item->id])?>" class="btn btn-danger">删除</a>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
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

