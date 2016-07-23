<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
$page = $this->gain('page');
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
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['content'];?></td>
                <td><?php echo $value['name'];?></td>
                <td><?php echo $value['email'];?></td>
                <td><?php echo $value['user_id'] > 0 ? '已注册' : '游客';?></td>
                <td><?php $this->time($value['create_at']);?></td>
                <td>[<a href="<?php $this->url('post/deleteComment/id/'.$value['id']);?>">删除</a>]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="8">
            <?php $page->pageLink();?>
        </th>
    </tr>
    </tfoot>
</table>
<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>