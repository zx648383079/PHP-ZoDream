<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\WeChat\Domain\Model\EditorTemplateModel;
/** @var $this View */
$this->title = '图文模板管理';
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>图文模板管理</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-primary pull-right" href="<?=$this->url('./@admin/template/create')?>">添加</a>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>类型</th>
                <th>内容</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($model_list as $item):?>
            <tr>
                    <td><?=$item->id?></td>
                    <td><?=$item->name?></td>
                    <td><?=EditorTemplateModel::$type_list[$item->type]?></td>
                    <td>
                        <div class="rich_media_content" style="width: 320px">
                            <?=$item->content?>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="<?=$this->url('./@admin/template/edit', ['id' => $item->id])?>" class="btn btn-default">编辑</a>
                            <a data-type="del" href="<?=$this->url('./@admin/template/delete', ['id' => $item->id])?>" class="btn btn-danger">删除</a>
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
