<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '资源管理';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>图文及媒体素材</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<div class="panel-container">
    <div class="page-search-bar">
        <div class="btn-group pull-right">
        <?php if(!$type || $type == 'news'):?>
            <a href="<?=$this->url('./@admin/media/create')?>" class="btn btn-primary">添加图文</a>
        <?php else:?>
            <a href="<?=$this->url('./@admin/media/create', ['type' => 'media'])?>" class="btn btn-info">添加素材</a>
        <?php endif;?>
        </div>
    </div>
    <table class="table table-hover">
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
                    <td class="right">
                       <div class="btn-group">
                        <?php if(empty($item->media_id) && $item->parent_id < 1):?>
                            <a href="<?=$this->url('./@admin/media/async', ['id' => $item->id])?>" class="btn btn-default">同步</a>
                            <?php endif;?>
                            <a href="<?=$this->url('./@admin/media/edit', ['id' => $item->id])?>" class="btn btn-info">编辑</a>
                            <a data-type="del" href="<?=$this->url('./@admin/media/delete', ['id' => $item->id])?>" class="btn btn-danger">删除</a>
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

