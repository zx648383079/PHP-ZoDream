<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '分组列表';
?>

<a class="btn btn-success" href="<?=$this->url('./@admin/group/create')?>">新增分组</a>
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
                        <a class="btn btn-default" href="<?=$this->url('./@admin/group/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/group/delete', ['id' => $item->id])?>">删除</a>
                    </td>
                </tr>
            <?php endforeach?>
            </tbody>
        </table>
    </div>
</div>

