<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Html\Page */
$this->extend('layout/header');
?>
<?=Html::a('增加用户', 'user/addUser', ['class' => 'btn'])?>
<?=Html::a('管理角色', 'user/role', ['class' => 'btn'])?>
<form method="GET">
    搜索： <input type="text" name="name" value="" placeholder="用户名" required>
    <button type="submit">搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>登录次数</th>
        <th>最后登录IP</th>
        <th>最后登陆时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) :?>
            <tr>
                <td><?=$value['id']?></td>
                <td><?=$value['name']?></td>
                <td><?=$value['email']?></td>
                <td><?=$value['login_num']?></td>
                <td><?=$value['update_ip']?></td>
                <td><?=$this->time($value['update_at'])?></td>
                <td>
                    [<?=Html::a('编辑', ['user/addUser', 'id' => $value['id']])?>]
                    [删除
                </td>
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

<?=$this->extend('layout/footer')?>