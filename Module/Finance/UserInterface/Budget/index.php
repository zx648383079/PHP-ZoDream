<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '生活预算';

$this->extend('layouts/header');
?>

    <a class="btn btn-success" href="<?=$this->url('./budget/add')?>">新增预算</a>
    <hr/>
    <div>
        <h2>生活预算</h2>
        <div class="col-xs-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>名称</td>
                    <td>预算(元)</td>
                    <td>已花费(元)</td>
                    <td>剩余(元)</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($model_list as $item): ?>
                    <tr class="<?=$item->remain < 0 ? 'danger' : ''?>">
                        <td>
                            <a href="<?=$this->url('./budget/statistics', ['id' => $item->id])?>" title="查看支出统计图">
                                <?=$item->name?>
                            </a>
                        </td>
                        <td>
                            <?=$item->budget?>
                        </td>
                        <td>
                            <?=$item->spent?>
                        </td>
                        <td>
                            <?=$item->remain?>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="<?=$this->url('./income/add_log', ['budget_id' => $item->id])?>">消费</a>
                            <a class="btn btn-primary" href="<?=$this->url('./budget/edit', ['id' => $item->id])?>">编辑</a>
                            <a class="btn btn-danger" data-type="post" href="<?=$this->url('./budget/delete', ['id' => $item->id])?>">删除</a>
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

<?php
$this->extend('layouts/footer');
?>