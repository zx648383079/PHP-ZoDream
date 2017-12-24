<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
$this->extend('layout/header');
?>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>内容</th>
        <th>是否首帖</th>
        <th>作者</th>
        <th>IP</th>
        <th>发布时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) :?>
            <tr>
                <td><?=$value['id']?></td>
                <td><?=$value['content']?></td>
                <td><?=$this->tag($value['first'], array('否', '是'));?></td>
                <td><?=$value['user_name']?></td>
                <td><?=$value['ip']?></td>
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