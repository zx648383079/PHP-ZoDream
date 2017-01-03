<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/header');
?>


<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">友情链接
        <?=Html::a('新增', 'link/add', ['class' => 'btn btn-primary'])?>
    </div>
        <div class="panel-body">
        </div>

        <!-- Table -->
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>名称</th> 
                <th>网址</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $value) :?>
                    <tr>
                        <td><?=$value['id']?></td>
                        <td><?=$value['name']?></td>
                        <td><?= $value['url']?></td>
                        <td>
                            <?=Html::a('新增', ['link/add', 'id' => $value['id']], ['class' => 'btn btn-primary'])?>
                            <?=Html::a('删除', ['link/delete', 'id' => $value['id']], ['class' => 'btn btn-danger'])?>
                        </td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
</div>

<?=$this->extend('layout/footer')?>