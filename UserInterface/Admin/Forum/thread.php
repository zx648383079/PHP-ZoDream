<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
/** @var $page \Zodream\Html\Page */
$this->extend('layout/header');
?>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>标题</th>
        <th>作者</th>
        <th>发布时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) :?>
            <tr>
                <td><?=$value['id']?></td>
                <td><?=$value['title']?></td>
                <td><?=$value['user_name']?></td>
                <td><?=$this->time($value['create_at'])?></td>
                <td>[删除]</td>
            </tr>
        <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="5">
            <?php $page->pageLink();?>
        </th>
    </tr>
    </tfoot>
</table>

<?=$this->extend('layout/footer')?>