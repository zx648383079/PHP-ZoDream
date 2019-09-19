<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '矿工种类';
?>

<a class="btn btn-success" href="<?=$this->url('./admin/miner/create')?>">新增工种</a>
<hr/>
<div>
    <div class="col-xs-12">
        <table class="table table-hover">
            <thead>
            <tr>
                <td>名称</td>
                <td>价格</td>
                <td>加成收益</td>
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
                        <?=$item->price?>
                    </td>
                    <td>
                        <?=$item->earnings?>
                    </td>
                    <td>
                        <a class="btn btn-primary" href="<?=$this->url('./admin/miner/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/miner/delete', ['id' => $item->id])?>">删除</a>
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