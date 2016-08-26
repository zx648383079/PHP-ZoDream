<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend('layout/head');
?>
<form method="GET">
    搜索： <input type="text" name="search" value="" placeholder="评论" required>
    <button type="submit">搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>评论</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>是否已注册</th>
        <th>评论时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) :?>
            <tr>
                <td><?=$value['id'];?></td>
                <td><?=$value['content'];?></td>
                <td><?=$value['name'];?></td>
                <td><?=$value['email'];?></td>
                <td><?=$value['user_id'] > 0 ? '已注册' : '游客';?></td>
                <td><?=$this->time($value['create_at']);?></td>
                <td>[<?=Html::a('删除', ['post/deleteComment', 'id' => $value['id']])?>]</td>
            </tr>
        <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="8">
            <?php $page->pageLink();?>
        </th>
    </tr>
    </tfoot>
</table>
<?=$this->extend('layout/foot')?>