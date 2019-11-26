<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '模块字段列表';
?>

   <a class="btn btn-success" href="<?=$this->url('./@admin/model/create_field', ['model_id' => $model->id])?>">新增字段</a>
    <hr/>

    <div>
        <div class="col-xs-12">
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
                            <?=$item->type_list[$item->type]?>
                        </td>
                        <td>
                            <a class="btn btn-default" href="<?=$this->url('./@admin/model/edit_field', ['id' => $item->id])?>">编辑</a>
                            <?php if($item->is_disable > 0):?>
                            <a class="btn btn-primary" data-type="ajax" href="<?=$this->url('./@admin/model/toggle_field', ['id' => $item->id, 'name' => 'is_disable'])?>">启用</a>
                            <?php else:?>
                            <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/model/toggle_field', ['id' => $item->id, 'name' => 'is_disable'])?>" data-tip="确定禁用此字段，禁用后将不能进行插入及更新">禁用</a>
                            <?php endif;?>
                            
                            <?php if($item->is_system < 1):?>
                            <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/model/delete_field', ['id' => $item->id])?>">删除</a>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>

