<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Template\View */
/** @var $page \Zodream\Html\Page */
$this->extend('layout/header');
?>
<div class="row">
    <div class="col-md-3 col-md-offset-2">
        <?=Html::a('新增', 'waste/add', [
            'class' => 'btn btn-primary'
        ])?>
    </div>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>编号</th>
        <th>名称</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) :?>
            <tr>
                <td><?=$value['id']?></td>
                <td><?=$value['code']?></td>
                <td><?=$value['name']?></td>
                <td><?php $this->time($value['update_at']);?></td>
                <td>
                    <?=Html::a('关联', ['waste/company', 'id' => $value['id']])?>
                    <?=Html::a('查看', ['waste/view', 'id' => $value['id']])?>
                    <?=Html::a('编辑', ['waste/add', 'id' => $value['id']])?>
                    <?=Html::a('删除', ['waste/delete', 'id' => $value['id']])?>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">
                <?php $page->pageLink();?>
            </th>
        </tr>
    </tfoot>
</table>

<?=$this->extend('layout/footer')?>