<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '联动项列表';
?>

<a class="btn btn-success" href="<?=$this->url('./admin/linkage/create_data', ['linkage_id' => $model->id, 'parent_id' => $parent_id])?>">新增联动项</a>
<hr/>

<div>
    <div class="col-xs-12">
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
                    <td><?=$item->name?></td>
                    <td>
                        <a class="btn btn-default" href="<?=$this->url('./admin/linkage/data', ['id' => $model->id, 'parent_id' => $item->id])?>">下一级</a>
                        <a class="btn btn-default" href="<?=$this->url('./admin/linkage/edit_data', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/linkage/delete_data', ['id' => $item->id])?>">删除</a>
                    </td>
                </tr>
            <?php endforeach?>
            </tbody>
        </table>
    </div>
</div>

