<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/head');
?>
<?=Html::a('增加角色', 'user/addRole', ['class' => 'btn'])?>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>角色名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $value) :?>
            <tr>
                <td><?=$value['id']?></td>
                <td><?=$value['name']?></td>
                <td>[<?=Html::a('编辑', ['user/addRole', 'id' => $value['id']])?>][删除]</td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

<?=$this->extend('layout/foot')?>