<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend('layout/head');
?>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>IP</th>
        <th>状态</th>
        <th>时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $item) :?>
            <tr>
                <td><?=$item['id']?></td>
                <td><?=$item['name']?></td>
                <td><?=$item['email']?></td>
                <td><?=$item['ip']?></td>
                <td><?php $this->tag($item['read'], ['未读', '已阅'])?></td>
                <td><?php $this->time($item['ip']);?></td>
                <td>
                    <?=Html::a('查看', ['feedback/view', 'id' => $item['id']])?>
                    <?=Html::a('删除', ['feedback/delete', 'id' => $item['id']])?>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">
                <?php $page->pageLink();?>
            </th>
        </tr>
    </tfoot>
</table>

<?=$this->extend('layout/foot')?>