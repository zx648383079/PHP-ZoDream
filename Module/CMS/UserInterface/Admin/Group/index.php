<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '分组列表';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>分组不会在前台显示，但是与前台模板有关</li>
        <li>例如：在模板中调用部分栏目且有后台灵活定义，那么就可以指定分组，由后台管理将一些栏目放入分组中即可</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/group/create')?>">新增分组</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <td>名称</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td class="left"><?=$item->name?></td>
                <td>
                    <div class="btn-group toggle-icon-text">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/group/edit', ['id' => $item->id])?>"
                        title="编辑详细信息">
                            <span>编辑</span>
                            <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/group/delete', ['id' => $item->id])?>" title="删除此分组">
                            <span>删除</span>
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach?>
        </tbody>
    </table>
    <?php if(empty($model_list)):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>
</div>

