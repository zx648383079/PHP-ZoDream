<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '矿场列表';
?>

<a class="btn btn-success" href="<?=$this->url('./admin/area/create')?>">新增矿场</a>
<hr/>
<div>
    <div class="col-xs-12">
        <table class="table table-hover">
            <thead>
            <tr>
                <td>名称</td>
                <td>剩余量</td>
                <td>当前矿工数</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model_list as $item): ?>
                <tr>
                    <td>
                        <?=$item->name?>
                    </td>
                    <td>
                        <?=0?>
                    </td>
                    <td>
                        <?=0?>
                    </td>
                    <td>
                        <a class="btn btn-primary" href="<?=$this->url('./admin/area/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/area/delete', ['id' => $item->id])?>">删除</a>
                    </td>
                </tr>
            <?php endforeach?>
            </tbody>
        </table>
    </div>
</div>
<div align="center">
    <?=$model_list->getLink()?>
</div>