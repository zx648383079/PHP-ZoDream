<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\CMS\Domain\Repositories\ModelRepository;
/** @var $this View */
$this->title = '模块字段列表';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/model/create_field', ['model_id' => $model->id])?>">新增字段</a>
    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <td>名称</td>
            <td>字段别名</td>
            <td>类型</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td><?=$item->name?></td>
                <td>
                    <?=$item->field?>
                </td>
                <td>
                    <?=ModelRepository::FIELD_TYPE_ITEMS[$item->type]?>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/model/edit_field', ['id' => $item->id])?>">编辑</a>
                        <?php if($item->is_disable > 0):?>
                        <a class="btn btn-primary" data-type="ajax" href="<?=$this->url('./@admin/model/toggle_field', ['id' => $item->id, 'name' => 'is_disable'])?>">启用</a>
                        <?php else:?>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/model/toggle_field', ['id' => $item->id, 'name' => 'is_disable'])?>" data-tip="确定禁用此字段，禁用后将不能进行插入及更新">禁用</a>
                        <?php endif;?>
                        
                        <?php if($item->is_system < 1):?>
                        <a class="btn btn-success" data-type="del" href="<?=$this->url('./@admin/model/delete_field', ['id' => $item->id])?>" data-tip="模型字段是所有站点公用的，确定删除？">删除</a>
                        <?php endif;?>
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
</div>

