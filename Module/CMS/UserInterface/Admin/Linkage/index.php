<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '联动菜单列表';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>联动菜单的别名可以相同，通过不同语言自动调用</li>
        <li>语言需跟对应站点的“本地化语言”一样才能正确切换</li>
        <li>当前列表数据都是后台管理用，具体前台页面显示的内容在 “联动项” 里管理添加</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/linkage/create')?>">新增联动菜单</a>
    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>名称</th>
            <th>语言</th>
            <th>项个数</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td class="left" title="<?=$item['code']?>"><?=$item['name']?></td>
                <td><?=$item['language']?></td>
                <td><?=$item['data_count']?></td>
                <td>
                   <div class="btn-group toggle-icon-text">
                        <a class="btn btn-primary" href="<?=$this->url('./@admin/linkage/data', ['id' => $item->id])?>" title="管理联动项列表">
                            <span>联动项</span>
                            <i class="fa fa-th-list"></i>
                        </a>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/linkage/edit', ['id' => $item->id])?>"
                        title="编辑详细信息">
                            <span>编辑</span>
                            <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/linkage/delete', ['id' => $item->id])?>" data-tip="联动菜单是所有站点公用的，确定删除？" title="删除此联动菜单">
                            <span>删除</span>
                            <i class="fa fa-trash"></i>
                        </a>
                   </div>
                </td>
            </tr>
        <?php endforeach?>
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

