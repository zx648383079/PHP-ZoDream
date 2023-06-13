<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '站点列表';
?>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/site/create')?>">新增站点</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <td>站点名</td>
            <td>网址</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td class="text-left">
                    <?php if($item->is_default):?>
                       <strong>[默认]</strong>
                    <?php endif;?>
                    <?=$item->title?>
                </td>
                <td class="text-left">[<?=$item->match_type < 1 ? '域名' : '路径'?>]<?=$item->match_rule ?: '(空)'?></td>
                <td>
                    <?php if($item->id != $current):?>
                            <a href="<?=$this->url('./@admin/site/change', ['id' => $item->id])?>" data-type="ajax">管理</a>
                        <?php else:?>
                            <a class="active" href="javascript:;">管理中</a>
                        <?php endif;?>
                    <div class="btn-group">
                        <?php if(!$item->is_default):?>
                            <a class="btn btn-info" data-type="ajax" href="<?=$this->url('./@admin/site/default', ['id' => $item->id])?>">设为默认</a>
                        <?php endif;?>
                        <a class="btn btn-primary" href="<?=$this->url('./@admin/site/option', ['id' => $item->id])?>">配置</a>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/site/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/site/delete', ['id' => $item->id])?>">删除</a>
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


