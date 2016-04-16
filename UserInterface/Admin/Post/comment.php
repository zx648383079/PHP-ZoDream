<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
$page = $this->get('page');
?>
<form method="GET">
    搜索： <input type="text" name="search" value="" placeholder="评论" required>
    <button type="submit">搜索</button>
</form>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>评论</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>是否已注册</th>
        <th>最后登录IP</th>
        <th>最后登陆时间</th>
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
                <td>[删除]</td>
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