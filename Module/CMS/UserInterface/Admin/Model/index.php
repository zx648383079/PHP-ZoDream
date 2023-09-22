<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '模块列表';
?>

<div class="panel-container">
    <div class="page-search-bar">

        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/model/create')?>">新增模块</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>名称</th>
            <th>表名</th>
            <th>类型</th>
            <th>字段数</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td><?=$item->name?></td>
                <td>
                    <?=$item->table?>
                </td>
                <td>
                    <?=$item->type > 0 ? '表单' : '实体'?>
                </td>
                <td>
                    <?=$item['field_count']?>
                </td>
                <td>
                    <div class="btn-group toggle-icon-text">
                        
                        <a class="btn btn-primary" href="<?=$this->url('./@admin/model/field', ['id' => $item->id])?>" title="管理模型下的字段列表">
                            <span>模块字段</span>
                            <i class="fa fa-th-list"></i>
                        </a>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/model/edit', ['id' => $item->id])?>"
                        title="编辑详细信息">
                            <span>编辑</span>
                            <i class="fa fa-edit"></i>
                        </a>

                        <a class="btn btn-success" href="<?=$this->url('./@admin/model/restart', ['id' => $item->id])?>" data-type="del" data-tip="您确定重新生成此模块，如果当前站点包含此模块，将删除此站点在此模块中的数据，确认则继续此操作" title="重新生成此模块">
                            <span>初始化</span>
                            <i class="fa fa-redo"></i>
                        </a>

                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/model/delete', ['id' => $item->id])?>"  data-tip="模型是所有站点公用的，确定删除？" title="删除此模型">
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

