<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Html\Page */

$this->extend('layout/header');
?>
<div class="row">
    <div class="col-md-3 col-md-offset-2">
        <?=Html::a('新增', 'company/add', ['class' => 'btn btn-primary'])?>
    </div>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>负责人</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($page->getPage() as $value) :?>
        <tr>
            <td><?=$value['id']?></td>
            <td><?=$value['name']?></td>
            <td><?=$value['charge']?></td>
            <td><?php $this->time($value['update_at']);?></td>
            <td>
                <?=Html::a('查看', ['company/view', 'id' => $value['id']])?>
                <?=Html::a('编辑', ['company/edit', 'id' => $value['id']])?>
                <?=Html::a('删除', ['company/delete', 'id' => $value['id']])?>
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